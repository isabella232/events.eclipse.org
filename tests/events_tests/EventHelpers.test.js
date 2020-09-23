import {
  checkEventWorkingGroups,
  checkEventTypes,
  checkFilterHasEvents,
  getSelectedItems,
  getEventsByWorkingGroups,
  getEventsByType,
  getSearchedEvents,
  getFilteredEvents,
  generateDates,
  generateTimes,
  WORKING_GROUPS,
  EVENT_TYPES
} from '../../js/components/EventHelpers';
import { 
  testEventData,
  filteredWorkingGroups,
  filteredEventTypes
} from './TestEventsData';

describe('Test checkEventWorkingGroups function', () => {

  const workingGroupFilter_I = { id: 'openadx' }
  const workingGroupFilter_II = { id: 'ascii_doc' }

  it('Test a working group has at least one event', () => {
      expect( checkEventWorkingGroups(testEventData, workingGroupFilter_I) ).toBe(true)
  });

  it('Test a working group does not have any event', () => {
      expect( checkEventWorkingGroups(testEventData, workingGroupFilter_II) ).not.toBe(true)
  });

});

describe('Test checkEventTypes function', () => {

  const eventTypeFilter_I = { id: 've' }
  const eventTypeFilter_II = { id: 'ec' }
  
  it('Test an event type has at least one event', () => {
      expect( checkEventTypes(testEventData, eventTypeFilter_I) ).toBe(true)
  });
  
  it('Test an event type does not have any event', () => {
      expect( checkEventTypes(testEventData, eventTypeFilter_II) ).not.toBe(true)
  });
    
});

describe('Test checkFilterHasEvents function', () => {

  it('Test a working group has at least one event', () => {
      expect( checkFilterHasEvents(WORKING_GROUPS, "WORKINGGROUPS", testEventData) ).toEqual(filteredWorkingGroups)
  });

  it('Test a working group does not have any event', () => {
      expect( checkFilterHasEvents(EVENT_TYPES, "EVENTTYPE", testEventData) ).toEqual(filteredEventTypes)
  });

  it('Test wrong filter type', () => {
    expect( checkFilterHasEvents(EVENT_TYPES, "", testEventData) ).toBeUndefined()
  });
  
});

describe('Test getSelectedItems function', () => {

  const selectedItems = {
    first: true,
    second: false,
    third: true
  }

  const emptySelectedItems_I = {
    first: false,
    second: false,
    third: false
  }

  const emptySelectedItems_II = {}

  it('Test render selected items', () => {
      expect( getSelectedItems(selectedItems) ).toEqual(['first', 'third'])
  });

  it('Test when no checkedItems', () => {
    expect( getSelectedItems(emptySelectedItems_I) ).toEqual([])
  });

  it('Test when no checkedItems', () => {
    expect( getSelectedItems(emptySelectedItems_II) ).toEqual([])
  });
});

describe('Test getEventsByWorkingGroups function', () => {

  const selectedItems = {
    ecd_tools: true,
    edge_native: true
  }

  const emptySelectedItems = {}

  it('Test get events by selecting some working groups', () => {
      const events = getEventsByWorkingGroups(selectedItems, testEventData)
      expect(events).toHaveLength(3)
      const titles = ["Open Source AI Workshop - S1E2", "Edge Computing World Conference", "EclipseCon 2020"]
      let result = events.filter(el => titles.indexOf(el.title) !== -1)
      expect(result).toHaveLength(3)
  });

  it('Test when not selecting any working groups', () => {
    expect( getEventsByWorkingGroups(emptySelectedItems, testEventData) ).toEqual(testEventData)
  });
});

describe('Test getEventsByType function', () => {

  const selectedItems = { ee: true }
  const emptySelectedItems = {}
  
  it('Test get events by selecting some event types', () => {
      const events = getEventsByType(selectedItems, testEventData)
      expect(events).toHaveLength(3)
      const titles = ["Capella Days - October 12-15", "Embedded World 2021", "Embedded Software Engineering Kongress 2020"]
      let result = events.filter(el => titles.indexOf(el.title) !== -1)
      expect(result).toHaveLength(3)
  });
  
  it('Test when not selecting any event types', () => {
    expect( getEventsByType(emptySelectedItems, testEventData) ).toEqual(testEventData)
  });

});

describe('Test getSearchedEvents function', () => {

  const searchedValue = 'spanish'
  const emptySearchedValue = ''
  
  it('Test get events by type searching', () => {
      const events = getSearchedEvents(testEventData, searchedValue)
      expect(events).toHaveLength(1)
      const titles = ["JakartaOne in Spanish"]
      let result = events.filter(el => titles.indexOf(el.title) !== -1)
      expect(result).toHaveLength(1)
  });
  
  it('Test when not typing any searching', () => {
    expect( getSearchedEvents(testEventData, emptySearchedValue) ).toEqual(testEventData)
  });
  
});

describe('Test getFilteredEvents function', () => {

  const selectedWorkingGroups_I = {
      opengenesis: true,
      openhwgroup: true
  }
  const selectedEventTypes_I = {
      ee: true
  }

  const selectedWorkingGroups_II = {
    opengenesis: true,
    openhwgroup: true,
    openmobility: true
  }

  const selectedEventTypes_II = {
    ve: true
  }

  const searchValues_I = "ec"

  const searchValues_II = "key"

  let emptySearchedValue

  const emptySelectedEventTypes = {}

  it('Test get events by using 2 filters I', () => {
    const events = getFilteredEvents(testEventData, emptySearchedValue, selectedWorkingGroups_I, selectedEventTypes_I)
    expect(events).toHaveLength(1)
    expect(events[0].title).toEqual("Embedded World 2021")
  });

  it('Test get events by using 2 filters II', () => {
    const events = getFilteredEvents(testEventData, searchValues_I, selectedWorkingGroups_I, emptySelectedEventTypes)
    expect(events).toHaveLength(1)
    expect(events[0].title).toEqual("EclipseCon 2020")
  });

  it('Test get events by using 3 filter I', () => {
    const events =  getFilteredEvents(testEventData, searchValues_I, selectedWorkingGroups_I, selectedEventTypes_I)
    expect(events).toHaveLength(0)
  });

  it('Test get events by using 3 filter II', () => {
    const events =  getFilteredEvents(testEventData, searchValues_II, selectedWorkingGroups_II, selectedEventTypes_II)
    expect(events).toHaveLength(1)
    expect(events[0].title).toEqual("Webinar Eclipse Keyple for Developers")
  });

});

describe('Test generateDates and generateTimes function', () => {

  const startDate_I = new Date('2020-10-13T12:30:00Z')
  const endDate_I = new Date('2020-10-14T21:00:00Z')

  const startDate_II = new Date('2020-10-12T12:00:00Z')
  let endDate_II
  let startDate_III
  const endDate_III = new Date('2020-10-12T21:00:00Z')
  
  it('Test generate correct dates', () => {
    expect(generateDates(startDate_I, endDate_I)).toBe('Tue, Oct 13 - Wed, Oct 14, 2020')
  });
  
  it('Test generate correct dates without end date', () => {
    expect( generateDates(startDate_II, endDate_II) ).toEqual('Mon, Oct 12')
  });

  it('Test generate correct start time for multiday events', () => {
    expect(generateTimes(startDate_I, endDate_I)).toBe('08:30 AM')
  });
  
  it('Test generate correct times for one day event', () => {
    expect( generateTimes(startDate_II, endDate_III) ).toEqual('08:00 AM - 05:00 PM')
  });

  it('Test when empty date for generateDates', () => {
    expect( generateDates(startDate_III, endDate_II) ).toBeUndefined()
  });

  it('Test when empty date for generateTimes', () => {
    expect( generateTimes(startDate_III, endDate_II) ).toBeUndefined()
  });
});