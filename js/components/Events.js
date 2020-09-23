import React, { useState } from 'react';
import EventCard from './EventCard';
import CustomSearch from './CustomSearch';
import CheckboxFilters from './CheckboxFilters';
import { getFilteredEvents } from './EventHelpers';
import PropTypes from 'prop-types';

const Events = ({ events }) => {

  const [searchValue, setSearchValue] = useState('')
  const [checkedWorkingGroups, setCheckedWorkingGroups] = useState({})
  const [checkedTypes, setCheckedTypes] = useState({})

  return (
    <div className="container">
      <div className="row margin-bottom-20">
        <div className="col-md-6">
          {/* Filters will be here */}
          <CustomSearch searchValue={searchValue} setSearchValue={setSearchValue} />
          <CheckboxFilters checkedTypes={checkedTypes} setCheckedTypes={setCheckedTypes} events={events} />
          <CheckboxFilters checkedWorkingGroups={checkedWorkingGroups} setCheckedWorkingGroups={setCheckedWorkingGroups} events={events} />
          <a className="btn btn-primary" href="https://newsroom.eclipse.org/node/add/events">Submit Your Event</a>
        </div>

        <div className="col-md-18 event-list-wrapper">
          {getFilteredEvents(events, searchValue, checkedWorkingGroups, checkedTypes).map((event) => (
            <div className="col-md-10 max-min-width" key={event.id}>
              <EventCard event={event} />
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

Events.propTypes = {
  events: PropTypes.array.isRequired,
}

export default Events;
