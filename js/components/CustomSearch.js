import React from 'react';
import PropTypes from 'prop-types';

const CustomSearch = ({ setSearchValue }) => {
  
  const handleSearchOnChange = (e) => {
    setSearchValue(e.target.value)
  }

  return (
    <div className="inner-addon left-addon">
      <i className="fa fa-search"></i>
      <input 
        type="text" 
        onChange={e => handleSearchOnChange(e)} 
        className="margin-bottom-10 margin-top-15 form-control" 
        placeholder="Search" 
      />
    </div>
  )
}

CustomSearch.propTypes = {
  setSearchValue: PropTypes.func.isRequired,
}


export default CustomSearch