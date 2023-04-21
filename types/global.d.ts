import {CurriedSelectorsOf} from '@wordpress/data/build-types/types'
import {default as store, NoticeStore} from '../src/scripts/store'
import {STORE_NAMESPACE} from '../src/scripts/constants';

declare global {
  interface Window { wp: { notifications: any }; wp_notifications_data?: { settingsPage: string } }
}

declare module '@wordpress/data' {
  function dispatch( key: typeof store | typeof STORE_NAMESPACE ): typeof import( '../src/scripts/store/actions' );
  function select( key: typeof store | typeof STORE_NAMESPACE ): CurriedSelectorsOf< NoticeStore >;
}
