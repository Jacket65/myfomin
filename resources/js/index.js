window.uuidv4 = function () {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}



window.fetchWithTimeout = async function (url, options = {}, timeoutMs = 2000) {
    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), timeoutMs);

    try {
        const res = await fetch(url, { ...options, signal: controller.signal });
        clearTimeout(id);
        return res;
    } catch (err) {
        clearTimeout(id);

        if (err.name === "AbortError") {
            throw new Error("Request timed out");
        }

        throw err;
    }
}


window.spamPostRequests = async function () {
    let attempt = 0;

    while (spamming) {
        await sendTransaction(attempt);
        await sleep(150); // невелика пауза між POST
    }
}

// api.js

const retryOptions = {
    retries: 5,
    firstDelay: 300,
    exponentBase: 2,
    jitter: 0.4,
};

const REQUEST_TIMEOUT = 1000;

function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

window.apiFetch = async function (url, method = 'GET', body = null) {
    const idempotencyKey = uuidv4();
    let response = null;

    for (let attempt = 0; ; attempt++) {
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort("Timeout"), REQUEST_TIMEOUT);

        try {
            response = await fetch(url, {
                method,
                body: body ? JSON.stringify(body) : null,
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text/html",
                    "X-Idempotency-Key": idempotencyKey,
                },
                signal: controller.signal,
            });
        } catch (e) {
            console.error("Network/timeout error:", e);
        }

        clearTimeout(timer);

        // RATE LIMIT
        if (response?.status === 429) {
            const delay = parseFloat(response.headers.get("Retry-After"));
            if (delay) await sleep(delay * 1000);
            continue;
        }

        // RETRY 5xx
        if (!response || response.status >= 500) {
            if (attempt < retryOptions.retries) {
                const base = retryOptions.firstDelay * retryOptions.exponentBase ** attempt;
                const jitter = base * (Math.random() * 2 - 1) * retryOptions.jitter;
                await sleep(base + jitter);
                continue;
            }
        }

        return response;
    }
};

// Універсальний парсер
window.handleApiResponse = async function (response) {
    const requestId = response.headers.get("X-Request-Id") ?? null;
    const contentType = response.headers.get("Content-Type") || "";

    let data;

    // JSON
    if (contentType.includes("application/json")) {
        data = await response.json().catch(() => ({}));
    }
    // HTML
    else if (contentType.includes("text/html")) {
        data = await response.text();
    }
    // інше
    else {
        data = await response.text();
    }

    if (!response.ok) {
        throw {
            error: "Request failed",
            code: response.status,
            details: data,
            requestId,
        };
    }

    return data;
};
