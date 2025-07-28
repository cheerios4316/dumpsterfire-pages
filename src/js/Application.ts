import "@compiled/ImportStyle";
import "@compiled/ImportScripts";

import {componentObserver} from "./Utils/ComponentObserver";
import {initializeComponents} from "./Utils/Initializer";

const observer = componentObserver(initializeComponents);

// Initialize components on DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
  observer.observe(document.body, { childList: true, subtree: true });

  initializeComponents();
});
