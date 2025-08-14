// @ts-ignore jquery is installed from the project, not from the framework
import $ from "jquery";

const getClassName = (element: HTMLElement): string|undefined => {
    return $(element)?.attr("class")?.split(" ").find((e) => e.endsWith("-component"));
}

const transformClassName = (name: string): string => {
    return name
        .split("-")
        .map((part: string) => part.charAt(0).toUpperCase() + part.slice(1))
        .join("")
        ;
}

/**
 * Turns html class name 'some-component' into js class name SomeComponent
 * @param element
 */
export const getComponentName = (element: HTMLElement): string|undefined => {
    let classname = getClassName(element);

    if (!classname) {
        return undefined;
    }

    return transformClassName(classname);
}