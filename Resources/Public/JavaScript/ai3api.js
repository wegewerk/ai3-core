import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

class Ai3Api {
    credits() {
        return new AjaxRequest(TYPO3.settings.ajaxUrls['ai3_credits'])
            .get();
    }
    account() {
        return new AjaxRequest(TYPO3.settings.ajaxUrls['ai3_account'])
            .get();
    }
}

export {Ai3Api as default};
