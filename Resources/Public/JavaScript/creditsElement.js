import {lll} from "@typo3/core/lit-helper.js";
import {html} from 'lit-html';
import {LitElement} from 'lit-element';
import Ai3Api from './ai3api.js'

class creditsElement extends LitElement {
    static properties ={
        credits: {type: Object},
    }
    constructor() {
        super();
        this.credits = {remaining:0};
    }
    reloadCredits() {
        let self = this;
            return Ai3Api.prototype.credits()
                .then(async function (response) {
                    const resolved = await response.resolve();
                    self.credits = JSON.parse(resolved);
                })
    }
    connectedCallback() {
        super.connectedCallback();
        this.reloadCredits();
        setInterval(this.reloadCredits.bind(this),10000);
    }
    render() {
        return html`
            ${lll('tx_ai3.module.zakai_setup.credits.remaining',this.credits.remaining)}
        `;
    }
}
customElements.define('ai3-credits',creditsElement);
