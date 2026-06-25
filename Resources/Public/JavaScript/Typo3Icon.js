import {AsyncDirective} from 'lit/async-directive.js';
import Icons from "@typo3/backend/icons.js";
import {directive} from 'lit/directive.js';
import {unsafeHTML} from 'lit/directives/unsafe-html.js';

const iconCache = new Map();

class LoadTYPO3Icon extends AsyncDirective {
    render(name) {
        let promise;
        if (iconCache.has(name)) {
            // prevent flickering: Render synchronously if cache hit
            return unsafeHTML(iconCache.get(name));
        } else {
            Icons.getIcon(name, Icons.sizes.small).then((T3Markup) => {
                iconCache.set(name, T3Markup);
                this.setValue(unsafeHTML(T3Markup));
            });
        }

        // Rendered synchronously:
        return `...`;
    }
}
const Typo3Icon = directive(LoadTYPO3Icon);

export {Typo3Icon as default};
