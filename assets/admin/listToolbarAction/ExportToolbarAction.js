import {translate} from 'sulu-admin-bundle/utils';
import {AbstractListToolbarAction} from 'sulu-admin-bundle/views';
import {computed} from 'mobx';
import symfonyRouting from 'fos-jsrouting/router';

export default class ExportToolbarAction extends AbstractListToolbarAction {
    getToolbarItemConfig() {
        const {disable_for_empty_selection: disableForEmptySelection = false} = this.options;

        return {
            type: 'button',
            label: translate('app.export'),
            icon: 'su-download',
            disabled: disableForEmptySelection && this.listStore.selections.length === 0,
            onClick: this.handleClick,
        };
    }

    handleClick = () => {
        window.location.assign(this.url);
    };

    @computed get url() {
        const {routeName} = this.options;

        if (typeof routeName !== 'string') {
            throw new Error('The "routeName" option must be a string!');
        }
        return symfonyRouting.generate(routeName);
    }
}
