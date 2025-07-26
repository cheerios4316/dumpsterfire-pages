// @ts-ignore jquery is installed from the project, not from the framework
import $ from "jquery";
import {getComponentName} from "./ComponentName";

export function initializeComponents(container = document) {
    $(container)
        .find('[class*="-component"]')
        .each(function () {
            const $element = $(this);
            const componentName = getComponentName(this);

            if (!componentName) {
                return;
            }

            if (!$element.data("initialized")) {
                if ((globalThis as any)[componentName]) {
                    new (globalThis as any)[componentName]($element);
                    $element.data("initialized", true);
                }
            }
        });
}