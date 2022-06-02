const { __ } = wp.i18n;
import {
  Component,
  createPortal,
  createContext,
  createElement,
  useState,
  useEffect,
  useReducer,
  render,
} from "@wordpress/element";
import demoImage from "../images/i.svg";
import wpLogo from "../images/wl.svg";

// delay util function
const delay = (ms) => new Promise((f) => setTimeout(f, ms));

/**
 * Toggle notification hub
 */
const wpNotifyHub = document.getElementById("wp-admin-bar-wp-notify");

const disableNotifyDrawer = () => {
  wpNotifyHub.classList.remove("active");
  document.body.removeEventListener("click", disableNotifyDrawer);
};

// handle click on wp-admin bar bell icon that show the WP-Notify sidebar
wpNotifyHub.addEventListener("click", function (e) {
  e.stopPropagation();
  if (!wpNotifyHub.classList.contains("active")) {
    this.classList.add("active");
    document.body.addEventListener("click", disableNotifyDrawer);
  }
});

// The dashboard notifications context
const NotifyContext = createContext();

/**
 * Enable the main dash notifications if available
 */
const notifyWrapper = document.getElementById("wp-notify-dashboard-notices");
if (notifyWrapper) {
  // Initialize the dashboard notification portal
  const NotificationsWrap = ({ children, elementId }) => {
    const [NotifyElement, setNotifyElement] = useState();

    useEffect(() => {
      const element = document.querySelector(`#${elementId}`);
      setNotifyElement(element);
    }, [elementId]);

    if (!NotifyElement) return null;

    return createPortal(children, NotifyElement);
  };

  // Handle changes and returns the array of displayed notifications
  const NotifyReducer = (state, data) => {
    switch (data.action) {
      case "ADD": {
        return [{ ...data.payload }, ...state];
      }
      case "REMOVE": {
        return state.filter((notification, id) => {
          return id !== data.payload;
        });
      }
      case "CLEAR_ALL": {
        return [];
      }
      default: {
        return state;
      }
    }
  };

  // Notification controller
  const NotifyController = ({ children }) => {
    const [state, dispatch] = useReducer(NotifyReducer, []);
    const addNotify = (props) => dispatch({ action: "ADD", payload: props });
    const removeNotify = (id) => dispatch({ action: "REMOVE", payload: id });
    const clearNotifies = () => dispatch({ action: "CLEAR_ALL" });

    window.addNotify = addNotify;
    window.removeNotify = removeNotify;
    window.clearNotifies = clearNotifies;

    return (
      <NotifyContext.Provider
        value={{ notification: state, addNotify, removeNotify, clearNotifies }}
      >
        {children}
      </NotifyContext.Provider>
    );
  };

  // The notification container class
  class Notifications extends Component {
    static context = NotifyContext;

    render() {
      return (
        <NotificationsWrap elementId="wp-notify-dashboard-notices">
          <NotifyContext.Consumer>
            {({ notification: notification, removeNotify }) =>
              notification.map((notify, id) => (
                <Notice
                  key={id}
                  id={id}
                  image={notify.image}
                  title={notify.title}
                  message={notify.message}
                  acceptMessage={notify.acceptMessage}
                  acceptLink={notify.acceptLink}
                  dismissLabel={notify.dismissLabel}
                  source={notify.source}
                  date={notify.date}
                  dismissible={notify.dismissible}
                  onDismiss={() => delay(100).then(() => removeNotify(id))}
                />
              ))
            }
          </NotifyContext.Consumer>
        </NotificationsWrap>
      );
    }
  }

  // Notice class method.
  class Notice extends Component {
    static defaultProps = {
      id: false,
      title: "",
      message: "",
      acceptMessage: __("Accept"),
      acceptLink: "#",
      dismissLabel: __("dismiss"),
      source: "WordPress",
      date: __("Just now"),
      dismissible: false,
      onDismiss: () => delay(100).then(() => this.unmount()),
    };

    render() {
      let classes = "wp-notification wp-notice-" + this.props.id;
      classes += this.props.dismissible ? " is-dismissible" : "";
      return (
        <div className={classes}>
          <div className="wp-notification-wrap">
            <h3 className="wp-notification-title">{this.props.title}</h3>
            <p dangerouslySetInnerHTML={{ __html: this.props.message }} />
            <div className="wp-notification-actions-wrap">
              <a
                className="button button-primary wp-notification-hub-trigger"
                href={this.props.acceptLink}
              >
                {this.props.acceptMessage}
              </a>
              {this.props.dismissible && (
                <button
                  className="button button-link wp-notification-hub-dismiss"
                  onClick={this.props.onDismiss}
                >
                  <span className="dashicons dashicons-no-alt"></span>
                  {this.props.dismissLabel}
                </button>
              )}
            </div>
            <p className="wp-notification-source">
              <span className="name">{this.props.source}</span> {"\u2022 "}
              <span className="date">{this.props.date}</span>
            </p>
          </div>
          {this.props.image && (
            <div className="wp-notification-image">
              <img src={this.props.image} alt={this.props.title + " image"} />
            </div>
          )}
        </div>
      );
    }
  }

  /**
   * Render the main dash notification container
   */
  render(
    <NotifyController>
      <Notifications />
    </NotifyController>,
    notifyWrapper
  );

  /**
   * add some demo notifications to the dashboard
   */
  if (
    pagenow &&
    (pagenow === "settings_page_wp-notify" || pagenow === "dashboard")
  )
    (async () =>
      await addNotify({
        image:
          "https://gifimage.net/wp-content/uploads/2018/10/animation-notification-gif-2.gif",
        title: "Message variant #3",
        message:
          "This is an example of on-page message variant #3. It has a title, a message, an action button with a URL, is dismissable, and has an image. To see the notification hub messages, click on the bell icon on the right side of the admin bar 😉.",
        acceptLink: "https://github.com/WordPress/wp-notify",
        dismissible: true,
      }))()
      .then(() => delay(600))
      .then(() =>
        addNotify({
          title: "Message variant #2",
          source: "#WP-Notify",
          date: new Date().toLocaleDateString(),
          message:
            "This is an example of on-page message variant #2. It has a title, a message, a custom date, an action button with a URL, is dismissable, but has no images.",
          acceptMessage: "OK",
          acceptLink: "https://github.com/WordPress/wp-notify",
          dismissible: true,
        })
      )
      .then(() => delay(1200))
      .then(() =>
        addNotify({
          image: "https://source.unsplash.com/random/400×400/?notify",
          title: "Message variant #1",
          source: "#Test",
          acceptMessage: "TEST",
          acceptLink: "https://github.com/WordPress/wp-notify",
        })
      )
      .then(() => delay(2400))
      .then(() =>
        addNotify({
          image: demoImage,
          title: "Try this new Notification feature",
          source: "#WP-Notify",
          message:
            "We have just added a <b>wonderful feature!</b> You might want to give it a try so click on the bell icon on the right side of the adminbar 😉.",
          acceptMessage: "Try this new feature",
          acceptLink: "https://github.com/WordPress/wp-notify",
          dismissible: false,
        })
      );
}

/**
 * the WP-Notify toolbar in the secondary position of the admin bar
 */
class HubNotice extends Component {
  render() {
    return (
      <>
        <section>
          <header>
            <h3>2 unread notifications</h3>
            <button className="wp-notification-action wp-notification-action-markread button-link">
              <span className="ab-icon dashicons-saved"></span> Mark all as read
            </button>
          </header>

          <div className="wp-notification plugin unread" role="status">
            <div className="wp-notification-image">
              <img src={wpLogo} />
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">WordPress</h4>
              <p className="wp-notification-message">
                WordPress was successfully updated to version 5.9.
              </p>
              <a href="#" className="wp-notification-action">
                Read what's new in 5.9
              </a>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification plugin unread" role="status">
            <div className="wp-notification-image default">
              <img src={wpLogo} />
            </div>
            <div className="wp-notification-wrap">
              <p className="wp-notification-message">
                WordPress was successfully updated to version 5.9.
              </p>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>
        </section>

        <section>
          <header>
            <h3>Older notifications</h3>
          </header>

          <div className="wp-notification plugin" role="status">
            <div className="wp-notification-image default">
              <img src={wpLogo} />
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">WordPress</h4>
              <p className="wp-notification-message">
                WordPress was successfully updated to version 5.9.
              </p>
              <a href="#" className="wp-notification-action">
                Read what's new in 5.9
              </a>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification plugin" role="status">
            <div className="wp-notification-image default">
              <img src={wpLogo} />
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">WordPress</h4>
              <p className="wp-notification-message">
                WordPress was successfully updated to version 5.9.
              </p>
              <a href="#" className="wp-notification-action">
                Read what's new in 5.9
              </a>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification plugin" role="status">
            <div className="wp-notification-image default">
              <img src="https://ps.w.org/contact-form-7/assets/icon-256x256.png" />
            </div>
            <div className="wp-notification-wrap">
              <p className="wp-notification-message">
                There is a new version of Contact Form 7 available.
              </p>
              <a href="#" className="wp-notification-action">
                Update now
              </a>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification plugin" role="status">
            <div className="wp-notification-image">
              <img
                src="https://ps.w.org/akismet/assets/icon-256x256.png"
                className="wp-notification-image"
              />
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">Akismet</h4>
              <a href="#" className="wp-notification-action">
                Your API key is no longer valid.
              </a>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">5d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification notify" role="status">
            <div className="wp-notification-image">
              <span className="ab-icon dashicons-paperclip"></span>
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">Default</h4>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">2d ago</span>
              </p>
            </div>
          </div>

          <div
            className="wp-notification notify wp-notification-error"
            role="status"
          >
            <div className="wp-notification-image">
              <span className="ab-icon dashicons-sos"></span>
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">Site Health status</h4>
              <p className="wp-notification-message">
                Your site has critical issues that should be addressed
              </p>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div
            className="wp-notification notify wp-notification-warning"
            role="status"
          >
            <div className="wp-notification-image">
              <span className="ab-icon dashicons-admin-plugins"></span>
            </div>
            <div className="wp-notification-wrap">
              <p className="wp-notification-message">
                Some plugins needs to be updated
              </p>
              <a href="#" className="wp-notification-action">
                Update now
              </a>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div
            className="wp-notification notify wp-notification-success"
            role="status"
          >
            <div className="wp-notification-image">
              <span className="ab-icon dashicons-megaphone"></span>
            </div>
            <div className="wp-notification-wrap">
              <h4 className="wp-notification-title">Word Camp Europe</h4>
              <p className="wp-notification-message">
                Word Camp was successfully updated to version 2022
              </p>
              <p className="wp-notification-source">
                <span className="name">WordPress</span> {"\u2022" + " "}
                <span className="date">1d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification user" role="status">
            <div className="wp-notification-image">
              <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" />
            </div>
            <div className="wp-notification-wrap">
              <p className="wp-notification-message">
                WordPress User has left a message on Lorem Ipsum.
              </p>
              <p className="wp-notification-source">
                <span className="name">Comment</span> {"\u2022" + " "}
                <span className="date">5d ago</span>
              </p>
            </div>
          </div>

          <div className="wp-notification user" role="status">
            <div className="wp-notification-image default">
              <img src="https://secure.gravatar.com/avatar/61ee2579b8905e62b4b4045bdc92c11a" />
            </div>
            <div className="wp-notification-wrap">
              <p className="wp-notification-message">
                Another user left a message on Hello World.
              </p>
              <p className="wp-notification-source">
                <span className="name">Comment</span> {"\u2022" + " "}
                <span className="date">6d ago</span>
              </p>
            </div>
          </div>
        </section>
      </>
    );
  }
}

render(createElement(HubNotice), document.getElementById("wp-notify-hub"));
