import React from 'react';
import ReactDOM from 'react-dom'
import Events from './components/Events'

const App = () => (
  <div className="container">
    <h1>Upcoming Events</h1>
    <Events/>
  </div>
);

ReactDOM.render(<App />, document.getElementById("app"));