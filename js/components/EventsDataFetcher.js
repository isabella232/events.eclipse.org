import React, { useState, useEffect } from 'react';
import Events from './Events';
import Loading from './Loading';
import { alertTypes } from './AlertsEnum';
import Alerts from './Alerts';
// Testing purpose, to be deleted
// To test, modify the events in the file, and pass testEventData into <Events events={testEventData} />
// import { testEventData } from './TestEventsData';

const EventsDataFetcher = () => {
  // Based off https://reactjs.org/docs/faq-ajax.html
  
  const [error, setError] = useState(null)
  const [isLoaded, setIsLoaded] = useState(false)
  const [events, setEvents] = useState([])
  
  // Note: the empty deps array [] means
  // this useEffect will run once
  // similar to componentDidMount()
  useEffect(() => {
    fetch('https://newsroom.eclipse.org/api/events?parameters[upcoming_only]=1&&options[orderby][field_event_date]=ASC')
      .then((res) => res.json())
      .then(
        (result) => {
          setIsLoaded(true)
          setEvents(result.events)
        },
        // Note: it's important to handle errors here
        // instead of a catch() block so that we don't swallow
        // exceptions from actual bugs in components.
        (error) => {
          setIsLoaded(true)
          setError(error)
        }
      )
  }, [])
  
  if (error) {
    return (
      <Alerts alertType={alertTypes.ERROR} message={error.message} />
    )
  } else if (!isLoaded) {
    return <Loading />
  } else {
    return (
      <Events events={events} />
    )
  }
}
  
export default EventsDataFetcher