import React from 'react';
import Select from 'react-select';
import makeAnimated from 'react-select/animated';

const animatedComponents = makeAnimated();
function parseCompanies(companies){
    return companies.map(company => {
        return { label: company.legal_name, value: company.id };
    });
}
const form = props => {
    return (
        <form className="detail" onSubmit={props.submit}>
            <h2>Recently change your contact detail? Let them know!</h2>
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

            <div className="form-group row">
                <label htmlFor="company">Target company</label>
                <Select
                    closeMenuOnSelect={false}
                    isMulti isSearchable
                    components={animatedComponents}
                    className="Select"
                    options={parseCompanies(props.companies)}
                    onChange={props.companyChange}
                    value={props.selectedCompanies}
                />
            </div>

            <button type="submit" className="btn btn-primary">
                Send Notification
            </button>
        </form>
    );
};

export default form;