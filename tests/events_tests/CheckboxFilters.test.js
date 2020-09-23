import React from 'react';
import CheckboxFilters from '../../js/components/CheckboxFilters';
import { testEventData } from './TestEventsData';
// Testing Lib
import { render, fireEvent, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

describe('Render checkbox filters', () => {
  const checkedWorkingGroups = {
      ecd_tools: true,
      edge_native: true
    }  
  const setCheckedWorkingGroups = jest.fn()

  const checkedTypes = {
    abc: true,
    def: true
  }  
const setCheckedTypes = jest.fn()
  
  it('Render working groups checkbox filters', () => {
    render(
      <CheckboxFilters
        checkedWorkingGroups={checkedWorkingGroups}
        setCheckedWorkingGroups={setCheckedWorkingGroups}
        events={testEventData}
      />
    )

    const checkbox = screen.getByTestId('sparkplug')
    fireEvent.click(checkbox)
    expect(setCheckedWorkingGroups).toBeCalledWith({
        ...checkedWorkingGroups,
        ['sparkplug']: true
    })
  })

  it('Render event types checkbox filters', () => {
    render(
      <CheckboxFilters
        checkedTypes={checkedTypes}
        setCheckedTypes={setCheckedTypes}
        events={testEventData}
      />
    )

    const checkbox = screen.getByTestId('ve')
    fireEvent.click(checkbox)
    expect(setCheckedTypes).toBeCalledWith({
        ...checkedTypes,
        ['ve']: true
    })
    
  })
})