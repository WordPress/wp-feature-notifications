import {CurriedSelectorsOf} from '@wordpress/data/build-types/types'
import {default as store, NoticeStore} from '../src/scripts/store'
import {NOTIFY_NAMESPACE} from '../src/scripts/store/constants';

declare global {
  interface Window { wp: { notify: any }; wp_notifications_data?: { settingsPage: string } }
}

declare module '@wordpress/data' {
  function dispatch( key: typeof store | typeof NOTIFY_NAMESPACE ): typeof import( '../src/scripts/store/actions' );
  function select( key: typeof store | typeof NOTIFY_NAMESPACE ): CurriedSelectorsOf< NoticeStore >;
}
