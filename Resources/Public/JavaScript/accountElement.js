import {lll} from "@typo3/core/lit-helper.js";
import {html} from 'lit-html';
import {LitElement} from 'lit-element';
import Ai3Api from './ai3api.js'

class accountElement extends LitElement {
    static properties ={
        account: {type: Object},
    }
    constructor() {
        super();
        this.account = {};
    }
    connectedCallback() {
        super.connectedCallback();
        let self = this;
        return Ai3Api.prototype.account()
            .then(async function (response) {
                const resolved = await response.resolve();
                self.account = JSON.parse(resolved);
            })
    }
    render() {
        return html`
            <dl>
                <dt>Email</dt><dd>${this.account.email}</dd>
                ${this.account.company ? html`<dt>Company</dt><dd>${this.account.company}</dd>`:''}
                ${this.account.link_profile ? html`<dt>Profil</dt><dd><a target="_blank" href="${this.account.link_profile}">mein Zak_ai Profil</a></dd>`:''}
            </dl>
            
        `;
    }
}
customElements.define('ai3-account',accountElement);
