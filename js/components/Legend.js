import React from 'react';
import { EVENT_ATTENDANCE_TYPE } from './EventHelpers';

const Legend = () => {

  return (
    <>
    <h2>Legend</h2>
    <hr />
    <ul className="event-legend-ul">
    { EVENT_ATTENDANCE_TYPE.map((item) => (
      <li className="event-legend-li" key={item.id}>
        <div className={`margin-left-5 event-legend event-legend-${item.id}`}></div>
        <p className="event-legend-p">{item.name}</p><br />
      </li>
    )) }
    </ul>
    </>
  )
}

export default Legend