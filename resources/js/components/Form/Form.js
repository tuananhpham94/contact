import React from 'react';
import Select from 'react-select';
import makeAnimated from 'react-select/animated';

const animatedComponents = makeAnimated();

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

            <div className="form-group row">
                <label htmlFor="company">Target company</label>
                <Select
                    closeMenuOnSelect={false}
                    isMulti isSearchable
                    components={animatedComponents}
                    className="Select"
                    options={[
                        { value: 'ANZ New Zealand', label: 'ANZ New Zealand' },
                        { value: 'ASB Bank', label: 'ASB Bank' },
                        { value: 'Bank of Baroda', label: 'Bank of Baroda' },
                        { value: 'Bank of China', label: 'Bank of China' },
                        { value: 'Bank of India', label: 'Bank of India' },
                        { value: 'Bank of Tokyo-Mitsubishi UFJ', label: 'Bank of Tokyo-Mitsubishi UFJ' },
                        { value: 'BankDirect New Zealand', label: 'BankDirect New Zealand' },
                        { value: 'BNZ', label: 'BNZ' },
                        { value: 'Citibank', label: 'Citibank' },
                        { value: 'Cooperative Bank', label: 'Cooperative Bank' },
                        { value: 'HBS Bank', label: 'HBS Bank' },
                        { value: 'Heartland Savings Bank', label: 'Heartland Savings Bank' },
                        { value: 'HSBC New Zealand', label: 'HSBC New Zealand' },
                        { value: 'Industrial and Commercial bank of China', label: 'Industrial and Commercial bank of China' },
                        { value: 'Kiwibank', label: 'Kiwibank' },
                        { value: 'Kookmin Bank', label: 'Kookmin Bank' },
                        { value: 'Rabobank', label: 'Rabobank' },
                        { value: 'RaboDirect', label: 'RaboDirect' },
                        { value: 'SBS Bank', label: 'SBS Bank' },
                        { value: 'TSB Bank', label: 'TSB Bank' },
                        { value: 'Westpac', label: 'Westpac' },
                        { value: 'IRD', label: 'IRD' },

                    ]}
                />
            </div>

            <button type="submit" className="btn btn-primary">
                Send Notification
            </button>
        </form>
    );
};

export default form;