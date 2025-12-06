<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8" />
    <title>Retry Test Client</title>

    <!-- CSRF Token (для Laravel web middleware) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { font-family: system-ui, sans-serif; padding: 20px; line-height: 1.6; }
        button { padding: 8px 14px; margin-right: 10px; }
        pre { background: #f5f5f5; padding: 14px; border-radius: 6px; }
    </style>
</head>
<body>
<h2>Retry Client (Exponential Backoff + Jitter + Timeout)</h2>

<label>
    Endpoint:
    <input id="endpoint" type="text" value="/test-retry" style="width:320px"/>
</label>
<br><br>

<button id="send">Send POST</button>
<button id="cancel" disabled>Cancel</button>

<h3>Log</h3>
<pre id="log">Ready.</pre>

<script>
    (function() {

        const elLog = document.getElementById('log');

        function log(...msg) {
            elLog.textContent =
                new Date().toISOString() + "  " + msg.join(' ') + "\n" + elLog.textContent;
        }

        const sleep = (ms) => new Promise(res => setTimeout(res, ms));

        /**
         * fetchWithResilience — ретраї з backoff + jitter + timeout
         */
        async function fetchWithResilience(url, options = {}) {
            const {
                retries = 4,
                baseDelayMs = 300,
                maxDelayMs = 10000,
                timeoutMs = 5000,
                jitter = true,
                method = 'GET',
                body = null,
                headers = {},
                signal = null,
                onAttempt = null
            } = options;

            let attempt = 0;
            let lastError = null;

            function computeDelay(a) {
                const exp = baseDelayMs * Math.pow(2, a);
                const capped = Math.min(exp, maxDelayMs);

                if (!jitter) return capped;

                const min = capped / 2;
                const max = capped;
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            for (; attempt <= retries; attempt++) {

                const controller = new AbortController();
                const combinedSignal = controller.signal;

                // зовнішній AbortController?
                if (signal) {
                    if (signal.aborted) controller.abort();
                    else signal.addEventListener("abort", () => controller.abort(), { once: true });
                }

                const timeoutId = setTimeout(() => controller.abort(), timeoutMs);

                try {
                    const nextDelay = computeDelay(attempt);
                    if (onAttempt) onAttempt(attempt + 1, nextDelay);

                    const res = await fetch(url, {
                        method,
                        headers,
                        body,
                        signal: combinedSignal
                    });

                    clearTimeout(timeoutId);

                    // 2xx → успіх
                    if (res.ok) {
                        const ct = res.headers.get('Content-Type') || '';
                        if (ct.includes('application/json')) {
                            return { response: res, data: await res.json(), attempt: attempt + 1 };
                        }
                        return { response: res, data: await res.text(), attempt: attempt + 1 };
                    }

                    // 5xx → retry
                    if (res.status >= 500 && res.status <= 599) {
                        lastError = new Error("Server error " + res.status);
                    } else {
                        // 4xx → без ретраю
                        throw new Error("HTTP " + res.status + ": " + await res.text());
                    }

                } catch (err) {

                    clearTimeout(timeoutId);

                    if (err.name === "AbortError")
                        throw new Error("Request aborted");

                    lastError = err;

                    if (attempt < retries) {
                        const delay = computeDelay(attempt);
                        log(`Attempt ${attempt + 1} failed: ${err.message}. Retry in ${delay} ms`);
                        await sleep(delay);
                    } else {
                        throw lastError;
                    }
                }
            }

            throw lastError || new Error("Unknown error.");
        }

        // UI
        const btn = document.getElementById('send');
        const btnCancel = document.getElementById('cancel');

        let extController = null;

        btn.addEventListener('click', async () => {
            const endpoint = document.getElementById('endpoint').value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            btn.disabled = true;
            btnCancel.disabled = false;
            log("Sending POST to", endpoint);

            extController = new AbortController();

            try {
                const result = await fetchWithResilience(endpoint, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        time: new Date().toISOString()
                    }),
                    signal: extController.signal,
                    retries: 5,
                    baseDelayMs: 300,
                    maxDelayMs: 8000,
                    timeoutMs: 4000,
                    jitter: true,
                    onAttempt(attemptNum, nextDelay) {
                        log(`Attempt ${attemptNum} (next delay ${nextDelay} ms)`);
                    }
                });

                log("SUCCESS on attempt", result.attempt);
                log("Response:", JSON.stringify(result.data, null, 2));

            } catch (err) {
                log("FINAL FAILURE:", err.message);
            } finally {
                btn.disabled = false;
                btnCancel.disabled = true;
                extController = null;
            }
        });

        btnCancel.addEventListener('click', () => {
            if (extController) {
                extController.abort();
                log("User cancelled request");
            }
        });

    })();
</script>

</body>
</html>
