import React from 'react';
import Events from '../../js/components/Events';
import { testEventData } from './TestEventsData';
// Testing Lib
import { render, fireEvent, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

describe('Test render events list and filters', () => {

  const firstEvent = testEventData.find(el => el.id == "37775")
  const secondEvent = testEventData.find(el => el.id == "37689")
  const thirdEvent = testEventData.find(el => el.id == "37765")  

  const type_I = 'Virtual Events'
  const type_II = 'Working Group Events'
  const type_III = 'Other interesting Events'

  const group_I = 'Eclipse Cloud Development Tools'
  const group_II = 'Edge Native'
  const group_III = 'Eclipse IoT'
  const group_IV = 'Jakarta EE'
  const group_V = 'OpenGENESIS'  

  it('Can render an event card', () => {

    render(
      <Events events={testEventData} />
    )
    expect(screen.getByText(firstEvent.title)).toBeInTheDocument()
    expect(screen.getByText(secondEvent.title)).toBeInTheDocument()
    expect(screen.getByText(thirdEvent.title)).toBeInTheDocument()
  });
  
  it('Can show event type filters', () => {

    render(
      <Events events={testEventData} />
    )  
    expect(screen.getByText(type_I)).toBeInTheDocument()
    expect(screen.getByText(type_II)).toBeInTheDocument()
    expect(screen.getByText(type_III)).toBeInTheDocument()
  });
  
  it('Can show working groups filters', () => {

    render(
      <Events events={testEventData} />
    )  
    expect(screen.getByText(group_I)).toBeInTheDocument()
    expect(screen.getByText(group_II)).toBeInTheDocument()
    expect(screen.getByText(group_III)).toBeInTheDocument()
    expect(screen.getByText(group_IV)).toBeInTheDocument()
    expect(screen.getByText(group_V)).toBeInTheDocument()
  });

  it('Can collapse event type filters', () => {
 
    render(
      <Events events={testEventData} />
    )
    fireEvent.click(screen.getByText('EVENT TYPE'))
    expect(screen.queryByText(type_I)).not.toBeInTheDocument()
    expect(screen.queryByText(type_II)).not.toBeInTheDocument()
    expect(screen.queryByText(type_III)).not.toBeInTheDocument()
  });

  it('Can collapse working groups filters', () => {
 
    render(
      <Events events={testEventData} />
    )
    fireEvent.click(screen.getByText('CATEGORIES'))
    expect(screen.queryByText(group_I)).not.toBeInTheDocument()
    expect(screen.queryByText(group_II)).not.toBeInTheDocument()
    expect(screen.queryByText(group_III)).not.toBeInTheDocument()
  });

  it('Can check working groups and filter events', () => {
 
    render(
      <Events events={testEventData} />
    )
    const checkbox = screen.getByTestId('sparkplug')
    fireEvent.click(checkbox)
    expect(checkbox.checked).toEqual(true)
    expect(screen.queryByText(firstEvent.title)).not.toBeInTheDocument()
    expect(screen.getByText(thirdEvent.title)).toBeInTheDocument()
    fireEvent.click(checkbox)
    expect(checkbox.checked).toEqual(false)
    expect(screen.queryByText(firstEvent.title)).toBeInTheDocument()
  });

  it('Can check event types and filter events', () => {
 
    render(
      <Events events={testEventData} />
    )
    const checkbox_ee = screen.getByTestId('ee')
    fireEvent.click(checkbox_ee)
    expect(checkbox_ee.checked).toEqual(true)
    const checkbox_wg = screen.getByTestId('wg')
    fireEvent.click(checkbox_wg)
    expect(checkbox_wg.checked).toEqual(true)
    expect(screen.queryByText(firstEvent.title)).not.toBeInTheDocument()
    expect(screen.getByText(thirdEvent.title)).toBeInTheDocument()

    fireEvent.click(checkbox_ee)
    fireEvent.click(checkbox_wg)
    expect(checkbox_ee.checked).toEqual(false)
    expect(checkbox_wg.checked).toEqual(false)
    expect(screen.queryByText(firstEvent.title)).toBeInTheDocument()
  });
});