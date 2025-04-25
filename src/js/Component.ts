export class Component {
    protected $element: any;

    constructor($element: JQuery<HTMLElement>) {
        this.$element = $element;

        this.setData && this.setData();

        this.init();
    }

    init() {
        this.defaultEvents();
        this.bindEvents && this.bindEvents();

        this.$element.data('instance', this)
    }

    protected setData?(): void;

    protected onClick?(e: JQuery.ClickEvent): void;

    protected bindEvents?(): void;


    private defaultEvents() {
        this.$element.on('click', (e: JQuery.ClickEvent) => {
            this.onClick && this.onClick(e);
        })
    }
}