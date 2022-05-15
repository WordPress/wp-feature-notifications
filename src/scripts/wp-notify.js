const { __ } = wp.i18n;

// handle click on wp-admin bar bell icon that show the WP-Notify sidebar
document
  .getElementById("wp-admin-bar-wp-notify")
  .addEventListener("click", function () {
    this.classList.toggle("active");
  });

// creates notify container
const { createPortal } = wp.element;

// the dashboard notifications context
const NotifyContext = wp.element.createContext();

const NotificationsWrap = ({ children, elementId }) => {
  const [NotifyElement, setNotifyElement] = wp.element.useState();

  wp.element.useLayoutEffect(() => {
    const element = document.querySelector(`#${elementId}`);
    setNotifyElement(element);
  }, [elementId]);

  if (!NotifyElement) return null;

  return createPortal(children, NotifyElement);
};

// Handle notification state
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

const DashNotifyController = ({ children }) => {
  const [state, dispatch] = wp.element.useReducer(NotifyReducer, []);
  const addNotify = ({ props }) =>
    dispatch({
      action: "ADD",
      payload: { id: props },
    });
  const removeNotify = (id) => dispatch({ action: "REMOVE", payload: id });
  const clearNotifies = () => dispatch({ action: "CLEAR_ALL" });

  console.log(state);

  return (
    <NotifyContext.Provider
      value={{ notification: state, addNotify, removeNotify, clearNotifies }}
    >
      {children}
    </NotifyContext.Provider>
  );
};

const Notifications = () => {
  const { notification, removeNotify } = wp.element.useContext(NotifyContext);

  return (
    <NotificationsWrap elementId="wp-notify-dashboard-notices">
      {notification.map((notify, id) => (
        <DashNotice
          key={id}
          id={id}
          image={notify.image}
          title={notify.title}
          message={notify.message}
          accept={notify.accept}
          dismiss={notify.dismiss}
          source={notify.source}
          date={notify.date}
          dismissible={notify.dismissible}
          onDismiss={() => removeNotify(id)}
        />
      ))}
    </NotificationsWrap>
  );
};

// Using the class method.
class DashNotice extends wp.element.Component {
  constructor(props) {
    super(props);
    this.buttonElement = wp.element.createRef(null);
  }

  render() {
    let classes = "wp-notification wp-notice-" + this.props.id;
    classes += this.props.dismissible ? " is-dismissible" : "";
    return (
      <div className={classes}>
        <div className="wp-notification-wrap">
          <h2 className="wp-notification-title">{this.props.title}</h2>
          <p>{this.props.message}</p>
          <div className="wp-notification-actions-wrap">
            <button
              className="button button-primary wp-notification-hub-trigger"
              href="#"
            >
              {this.props.accept}
            </button>
            <button
              className="button button-link wp-notification-hub-dismiss"
              onClick={this.props.onDismiss}
              ref={this.buttonElement}
            >
              <span className="dashicons dashicons-no-alt"></span>
              {__("dismiss")}
            </button>
          </div>
          <p className="wp-notification-source">
            <span className="name">{this.props.source}</span> {"\u2022 "}
            <span className="date">{this.props.date}</span>
          </p>
        </div>
        {this.props.image && (
          <div className="wp-notification-image">
            <img src={this.props.image} alt={this.props.title} />
          </div>
        )}
      </div>
    );
  }
}

const Demo = () => {
  const { addNotify, clearNotifies } = wp.element.useContext(NotifyContext);
  return (
    <div className="demo">
      <button
        className="button button-primary"
        onClick={() => addNotify({ title: "Hello world!" })}
      >
        {__("Add")}
      </button>
      <button className="button button-primary" onClick={clearNotifies}>
        {__("Clear all")}
      </button>
    </div>
  );
};

wp.element.render(
  <DashNotifyController>
    <Demo />
    <Notifications />
  </DashNotifyController>,
  document.getElementById("wp-notify-dashboard-notices")
);

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
      </section>
    );
  }
}

DashNotice.defaultProps = {
  image: false,
  id: false,
  title: "",
  message: "",
  accept: __("Accept"),
  dismiss: __("Dismiss"),
  source: "Wordpress",
  date: __("Just now"),
  dismissible: true,
};

wp.element.render(
  <DashNotice
    image="https://source.unsplash.com/random/400×200"
    title="Try this new Notification feature"
    source="#WP-Notify"
    message="We have just added a wonderful feature! you might want to give it a try so click on the bell icon on the right side of the adminbar 😉."
    accept="Try this new feature"
  />,
  document.getElementById("wp-notify-notice-demo")
);

wp.element.render(
  wp.element.createElement(HubNotice),
  document.getElementById("wp-notify-hub")
);
