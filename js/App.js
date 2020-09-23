// These must be the first lines in src/index.js
import 'react-app-polyfill/ie11';
import 'react-app-polyfill/stable';
import React from 'react';
import ReactDOM from 'react-dom';
import EventsDataFetcher from './components/EventsDataFetcher';

const App = () => (
  <div className="container">
    <h1>Upcoming Events</h1>
    <EventsDataFetcher />
  </div>
);

ReactDOM.render(<App />, document.getElementById("app"));