import "@root/public/dist/tailwind.css";

// @ts-ignore
const userComponentContext = require.context("@root/src", true, /\.css$/);

// @ts-ignore
const frameworkComponentContext = require.context("@vendor/dumpsterfire-pages/src", true, /\.css$/);

const contexts = [
    userComponentContext, frameworkComponentContext
];

for(let context of contexts) {
    context.keys().forEach((key: any) => {
        context(key);
    })
}