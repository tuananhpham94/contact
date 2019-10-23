import React from 'react';

const form = props => {
    return (
        <form className="detail" onSubmit={props.submit}>
            <div className="form-group row">
                <label htmlFor="email">Email address</label>
                <input
                    onChange={props.emailChange}
                    id="email" type="email"
                    className="form-control"
                    name="email"
                    value={props.email}
                    autoFocus />
            </div>
            <div className="form-group row">
                <label htmlFor="tel"> Phone number</label>
                <input id="tel" type="text"
                       onChange={props.telChange}
                       className="form-control"
                       name="tel"
                       value={props.tel}
                       autoFocus />
            </div>
            <div className="form-group row">
                <label htmlFor="address">Address</label>
                <input id="address" type="text"
                       onChange={props.addressChange}
                       className="form-control"
                       name="address"
                       value={props.address}
                       autoFocus />
            </div>
            <button type="submit" className="btn btn-primary">
                Change
            </button>
        </form>
    );
};

export default form;