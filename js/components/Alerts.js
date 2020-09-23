import React from 'react';

const Alerts = ({ alertType, message }) => {

  switch(alertType) {
    case 'ERROR':
      return (
        <div className="alert alert-danger" role="alert">
          Oops, an error occurred: {message}
        </div>
      )

    case 'WARNING':
      return (
        <div className="alert alert-warning" role="alert">
          Warning! {message}
        </div>
      )

    case 'INFO':
      return (
        <div className="alert alert-info" role="alert">
          {message}
        </div>
      )

    case 'SUCCESS':
      return (
        <div className="alert alert-success" role="alert">
          {message}
        </div>
      )
    
    default: 
      return ''
  }

}

export default Alerts