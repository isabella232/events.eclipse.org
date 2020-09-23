import React, { useState } from 'react';
import Checkbox from './Checkbox';
import { EVENT_TYPES, WORKING_GROUPS, checkFilterHasEvents, alphaOrder } from './EventHelpers';
import PropTypes from 'prop-types';

const CheckboxFilters = ({ checkedTypes, setCheckedTypes, checkedWorkingGroups, setCheckedWorkingGroups, events }) => {
  
  const determineInitialState = () => {
    return window.innerWidth > 991
  }

  const [showTypes, setShowTypes] = useState(determineInitialState())
  const [showWorkingGroups, setShowWorkingGroups] = useState(determineInitialState())

  const handleChange = (e) => {
    if (checkedWorkingGroups && setCheckedWorkingGroups) {
      if (e.target.checked) {
        setCheckedWorkingGroups({
         ...checkedWorkingGroups,
         [e.target.name]: e.target.checked
       });
      } else {
        setCheckedWorkingGroups({
           ...checkedWorkingGroups,
          [e.target.name]: undefined
        })
      }
    }

    if (checkedTypes && setCheckedTypes) {
      if (e.target.checked) {
        setCheckedTypes({
         ...checkedTypes,
         [e.target.name]: e.target.checked
       });
      } else {
        setCheckedTypes({
           ...checkedTypes,
          [e.target.name]: undefined
        })
      }
    }

  }

  const toggleTypes = () => {
    setShowTypes(!showTypes)
  }

  const toggleWorkingGroups = () => {
    setShowWorkingGroups(!showWorkingGroups)
  }

  const WorkingGroups = () => {
    if (checkedWorkingGroups && setCheckedWorkingGroups) {
      return (
        <> 
          <button
            onClick={toggleWorkingGroups} 
            className="event-filter-title"
            >
              CATEGORIES
              <i className="fa fa-angle-down event-filter-expandable-icon" aria-hidden="true"></i>
          </button>
          { showWorkingGroups && 
            <ul className="event-filter-checkbox-list">
                { alphaOrder(checkFilterHasEvents(WORKING_GROUPS, "WORKINGGROUPS", events)).map(item => (
                  <li key={item.id}>
                    <label key={item.id}>
                      <Checkbox
                        name={item.id} 
                        checked={checkedWorkingGroups[item.id]} 
                        onChange={handleChange}
                      />
                      {item.name}
                    </label>
                  </li>
                ))}
            </ul>
          }
        </>
      )
    }
  }


  const EventTypes = () => {
    if (checkedTypes && setCheckedTypes) {
      return (
        <>
          <button
            onClick={toggleTypes}
            className="event-filter-title"
          >
            EVENT TYPE
            <i className="fa fa-angle-down event-filter-expandable-icon" aria-hidden="true"></i>
          </button>
          { showTypes &&
            <ul className="event-filter-checkbox-list">
                { alphaOrder(checkFilterHasEvents(EVENT_TYPES, "EVENTTYPE", events)).map(item => (
                  <li key={item.id}>
                    <label key={item.id}>
                      <Checkbox
                        name={item.id}
                        checked={checkedTypes[item.id]}
                        onChange={handleChange}
                      />
                      {item.name}
                      <div className={`margin-left-5 event-legend event-legend-${item.id}`}></div>
                    </label>
                  </li>
                )) }
            </ul>
          }
        </>
      )
    }
  }

  return (
    <div className="margin-bottom-10">
      {WorkingGroups()}
      {EventTypes()}
    </div>
  )
}

CheckboxFilters.propTypes = {
  checkedTypes: PropTypes.object,
  setCheckedTypes: PropTypes.func,
  checkedWorkingGroups: PropTypes.object,
  setCheckedWorkingGroups: PropTypes.func,
}

export default CheckboxFilters