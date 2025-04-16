export class Component {
    private $element: any;
    constructor($element: any) {
        this.$element = $element;
        this.setData();
        this.setDependencies();
        this.init();
    }

    init() {
        this.bindEvents();
        this.$element.data('instance', this)
    }

    setDependencies() {}

    setData() {}

    bindEvents() {}
}