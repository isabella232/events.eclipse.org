import React, { useState, useEffect } from 'react';

const Events = () => {
  // Based off https://reactjs.org/docs/faq-ajax.html

  const [error, setError] = useState(null);
  const [isLoaded, setIsLoaded] = useState(false);
  const [events, setEvents] = useState([]);

  // Note: the empty deps array [] means
  // this useEffect will run once
  // similar to componentDidMount()
  useEffect(() => {
    fetch('https://newsroom.eclipse.org/api/events?parameters[upcoming_only]=1')
      .then((res) => res.json())
      .then(
        (result) => {
          setIsLoaded(true);
          setEvents(result.events);
        },
        // Note: it's important to handle errors here
        // instead of a catch() block so that we don't swallow
        // exceptions from actual bugs in components.
        (error) => {
          setIsLoaded(true);
          setError(error);
        }
      );
  }, []);

  if (error) {
    return <div>Error: {error.message}</div>;
  } else if (!isLoaded) {
    return <div>Loading...</div>;
  } else {
    return (
      <div class="list-group">
        {events.map((event) => (
          <a href={event.infoLink} class="list-group-item" key={event.id}>
            <h4>
              {event.title}{' '}
              <span className="badge badge-primary">
                {new Date(event.date).toLocaleDateString()}
              </span>
            </h4>
            <p class="list-group-item-text">{event.description}</p>
          </a>
        ))}
      </div>
    );
  }
};

export default Events;
