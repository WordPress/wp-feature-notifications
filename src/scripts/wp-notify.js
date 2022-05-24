const { __ } = wp.i18n;

// delay util function
const delay = (ms) => new Promise((f) => setTimeout(f, ms));

// handle click on wp-admin bar bell icon that show the WP-Notify sidebar
document
  .getElementById("wp-admin-bar-wp-notify")
  .addEventListener("click", function () {
    this.classList.toggle("active");
  });

// This will create the dashboard notification container element
const { createPortal } = wp.element;

// The dashboard notifications context
const NotifyContext = wp.element.createContext();

// Initialize the dashboard notification portal
const NotificationsWrap = ({ children, elementId }) => {
  const [NotifyElement, setNotifyElement] = wp.element.useState();

  wp.element.useEffect(() => {
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
const DashNotifyController = ({ children }) => {
  const [state, dispatch] = wp.element.useReducer(NotifyReducer, []);
  const addNotify = (props) => dispatch({ action: "ADD", payload: props });
  const removeNotify = (id) => dispatch({ action: "REMOVE", payload: id });
  const clearNotifies = () => dispatch({ action: "CLEAR_ALL" });

  window.addNotify = addNotify;
  window.removeNotify = removeNotify;
  window.clearNotifies = clearNotifies;

  console.log(state);

  return (
    <NotifyContext.Provider
      value={{ notification: state, addNotify, removeNotify, clearNotifies }}
    >
      {children}
    </NotifyContext.Provider>
  );
};

class Notifications extends wp.element.Component {
  static context = NotifyContext;

  render() {
    return (
      <NotificationsWrap elementId="wp-notify-dashboard-notices">
        <NotifyContext.Consumer>
          {({ notification: notification, removeNotify }) =>
            notification.map((notify, id) => (
              <DashNotice
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

// DashNotice class method.
class DashNotice extends wp.element.Component {
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

DashNotice.defaultProps = {
  id: false,
  image: false,
  title: "",
  message: "",
  acceptMessage: __("Accept"),
  acceptLink: "#",
  dismissLabel: __("dismiss"),
  source: "WordPress",
  date: __("Just now"),
  dismissible: true,
};

/**
 * THIS IS FOR TESTING PURPOSE - TO BE REMOVED
 */

/**
 * Render the main dash notification container
 */
if (pagenow === "dashboard")
  wp.element.render(
    <DashNotifyController>
      <Notifications />
    </DashNotifyController>,
    document.getElementById("wp-notify-dashboard-notices")
  );

/**
 * THIS IS FOR TESTING PURPOSE - TO BE REMOVED
 */
delay(2000).then(() =>
  addNotify({
    image:
      "https://gifimage.net/wp-content/uploads/2018/10/animation-notification-gif-2.gif",
    title: "Try this new Notification feature",
    source: "#WP-Notify",
    message:
      "We have just added a <b>wonderful feature!</b> You might want to give it a try so click on the bell icon on the right side of the adminbar 😉.",
    acceptMessage: "Try this new feature",
    acceptLink: "https://github.com/WordPress/wp-notify",
    dismissible: false,
  })
);

// the WP-Notify toolbar in the secondary position of the admin bar
class HubNotice extends wp.element.Component {
  render() {
    return (
      <section>
        <header>
          <h3>2 unread notifications</h3>
          <button className="wp-notification-action wp-notification-action-markread button-link">
            <span className="ab-icon dashicons-saved"></span> Mark all as read
          </button>
        </header>
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
        <div className="wp-notification plugin" role="status">
          <div className="wp-notification-image default">
            <img
              src="https://ps.w.org/contact-form-7/assets/icon-256x256.png"
              alt="contact form 7 icon"
            />
          </div>
          <div className="wp-notification-wrap">
            <p className="wp-notification-message">
              There is a new version of Contact Form 7 available.
            </p>
            <a href="./wp-admin/plugins.php" className="wp-notification-action">
              Update now
            </a>
            <p className="wp-notification-source">
              <span className="name">WordPress</span> {"\u2022" + " "}
              <span className="date">1d ago</span>
            </p>
          </div>
        </div>
      </section>
    );
  }
}

wp.element.render(
  wp.element.createElement(HubNotice),
  document.getElementById("wp-notify-hub")
);
