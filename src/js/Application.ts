import $ from "jquery";

// @ts-ignore
const context = require.context("/public/js", true, /\.js$/);
context.keys().forEach(context);

function initializeComponents(container = document) {
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

function getComponentName(element: HTMLElement) {
  // @ts-ignore
  let classname = $(element)
    .attr("class")
    .split(" ")
    .find((e) => e.endsWith("-component"));
  if (!classname) {
    return null;
  }

  // turn html class name 'some-component' into js class name SomeComponent
  return classname
    .split("-")
    .map((part: string) => part.charAt(0).toUpperCase() + part.slice(1))
    .join("");
}

const observer = new MutationObserver((mutationsList) => {
  for (const mutation of mutationsList) {
    if (mutation.type === "childList") {
      mutation.addedNodes.forEach((node) => {
        if (node.nodeType === 1) {
          // element node
          initializeComponents();
        }
      });
    }
  }
});

// Initialize components on DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
  observer.observe(document.body, { childList: true, subtree: true });

  initializeComponents();
});
