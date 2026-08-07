import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

class Ai3Api {
    static CREDIT_COSTS_STORAGE_KEY = 'ai3_credit_costs_cache';

    constructor() {
        this._creditCostsInflight = null;
    }

    credits() {
        return new AjaxRequest(TYPO3.settings.ajaxUrls['ai3_credits'])
            .get();
    }

    // Default TTL ist 4h (4 * 60 * 60 * 1000 ms)
    // Cache wird in sessionStorage gespeichert.
    creditCosts({ ttl = 4 * 60 * 60 * 1000 } = {}) {
        const cached = this._readCreditCostsCache();
        if (cached && Date.now() < cached.expireAt) {
            return Promise.resolve(cached.data);
        }
        if (!this._creditCostsInflight) {
            this._creditCostsInflight = this.credits()
                .then(response => response.resolve())
                .then(raw => JSON.parse(raw))
                .then(data => {
                    const costs = data.credit_costs || {};
                    this._writeCreditCostsCache(costs, ttl);
                    this._creditCostsInflight = null;
                    return costs;
                })
                .catch(error => {
                    this._creditCostsInflight = null;
                    throw error;
                });
        }
        return this._creditCostsInflight;
    }

    _readCreditCostsCache() {
        try {
            const raw = sessionStorage.getItem(Ai3Api.CREDIT_COSTS_STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch {
            return null;
        }
    }

    _writeCreditCostsCache(data, ttl) {
        try {
            sessionStorage.setItem(
                Ai3Api.CREDIT_COSTS_STORAGE_KEY,
                JSON.stringify({ expireAt: Date.now() + ttl, data })
            );
        } catch {
            // storage unavailable; the cache simply will not persist
        }
    }

    account() {
        return new AjaxRequest(TYPO3.settings.ajaxUrls['ai3_account'])
            .get();
    }
}

export {Ai3Api as default};
