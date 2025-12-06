@extends('layouts.main')
@section('content')

<div id="layoutSidenav_content">

    <div class="w-full px-6 py-10">
        <button type="button" class="btn btn-warning" id="spam">Create</button>
        <div id="status"></div>
        <h1 class="text-3xl font-bold mb-6 text-gray-900">Transaction History</h1>

        {{-- Check if there are transactions --}}
        @if($transactions->isEmpty())

            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No transactions found.
            </div>
        @else
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Amount</th>

                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ date('M d, Y H:i', strtotime($transaction->created_at)) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $transaction->category ? $transaction->category->name : 'Без категорії' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{$transaction->description }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold {{ $transaction->type == "spend"? 'text-red-500' : 'text-green-600' }}">
                                {{ $transaction->type == "spend" ? '-' : '+' }}${{ number_format(abs($transaction->amount), 2) }}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>


<script>
    // ---------------------------
    // UUID (крос-браузерний)
    // ---------------------------
    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // ---------------------------
    // Sleep helper
    // ---------------------------
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // ---------------------------
    // FETCH WITH TIMEOUT
    // ---------------------------
    async function fetchWithTimeout(url, options = {}, timeoutMs = 2000) {
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

    // ---------------------------
    // EXPONENTIAL BACKOFF + JITTER
    // backoff = base * 2^attempt + random(0..250)
    // ---------------------------
    function getBackoffDelay(attempt) {
        const base = 300; // базова затримка 300 мс
        const jitter = Math.floor(Math.random() * 250);
        return base * Math.pow(2, attempt) + jitter;
    }

    // ---------------------------
    // UI elements
    // ---------------------------
    const btn = document.getElementById('spam');
    const status = document.getElementById('status');

    let spamming = false;

    // Старт/стоп спаму
    btn.addEventListener('click', () => {
        spamming = !spamming;

        if (spamming) {
            btn.innerText = "Stop spam";
            spamPostRequests();
        } else {
            btn.innerText = "Start spam";
        }
    });

    // ---------------------------
    // Main spam loop
    // ---------------------------
    async function spamPostRequests() {
        let attempt = 0;

        while (spamming) {
            await sendTransaction(attempt);
            await sleep(150); // невелика пауза між POST
        }
    }

    // ---------------------------
    // POST /transactions + full resilience
    // ---------------------------
    async function sendTransaction(attempt) {
        const idempotencyKey = uuidv4();

        try {
            const res = await fetchWithTimeout('api/transactions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Idempotency-Key': uuidv4(),
                },
                body: JSON.stringify({ "amount": Math.floor(Math.random() * 100), "type":'spend', 'account_id':1, 'category_id':1, }),

            }, 1000);

            // -------------------------
            // RATE-LIMIT 429 + RETRY-AFTER
            // -------------------------
            if (res.status === 429) {
                const retryAfter = Number(res.headers.get("Retry-After") || 1);

                // Degraded mode → ON
                btn.disabled = true;
                status.innerText = `Server overloaded. Waiting ${retryAfter}s...`;

                await sleep(retryAfter * 1000);

                // Degraded mode → OFF
                btn.disabled = false;
                status.innerText = "";

                return; // цикл продовжиться сам → retry
            }

            // -------------------------
            // SERVER ERROR → RETRY WITH BACKOFF
            // -------------------------
            if (res.status >= 500) {
                const delay = getBackoffDelay(attempt);
                status.innerText = `Server error ${res.status}. Retrying in ${delay}ms...`;
                await sleep(delay);
                return;
            }

            // -------------------------
            // Все ок
            // -------------------------
            const data = await res.json();
            console.log("Created:", data);
            status.innerText = "";
            return;

        } catch (err) {
            // -------------------------
            // TIMEOUT → RETRY WITH BACKOFF
            // -------------------------
            if (err.message === "Request timed out") {
                const delay = getBackoffDelay(attempt);
                status.innerText = `Timeout. Retrying in ${delay}ms...`;
                await sleep(delay);
                return;
            }

            console.error("Network error:", err);
        }
    }

</script>

@endsection
