import {CurriedSelectorsOf} from '@wordpress/data/build-types/types'
import {NoticeStore, NOTIFY_NAMESPACE} from './src/scripts/store'

declare global {
  interface Window { wp: { notify: any }; wp_notifications_data?: { settingsPage: string } }
}

declare module '@wordpress/data' {
  function dispatch( key: NOTIFY_NAMESPACE ): typeof import( './src/scripts/store/actions' );
  function select( key: NOTIFY_NAMESPACE ): CurriedSelectorsOf< NoticeStore >;
}
