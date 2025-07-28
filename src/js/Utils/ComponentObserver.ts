const NODE_TYPE: Record<string, number> = {
    ELEMENT: 1
} as const;

const MUTATION_TYPE: Record<string, string> = {
    CHILD_LIST: "childList"
} as const;

type TRegisterCallback = (container?: Document) => void;

export const componentObserver = (callback: TRegisterCallback): MutationObserver => {
    return new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            registerMutation(mutation, callback);
        }
    });
}

const registerMutation = (mutation: MutationRecord, callback: TRegisterCallback): void => {
    if (mutation.type !== MUTATION_TYPE.CHILD_LIST) {
        return;
    }

    mutation.addedNodes.forEach((node) => {

        // element node
        if (node.nodeType === NODE_TYPE.ELEMENT) {
            callback();
        }
    });
}