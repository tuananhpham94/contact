import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class History extends Component {
    constructor(props) {
        super(props);
    }
    renderHistory(history) {
        return history.map(history => (
            <tr key={history.id}>
                <td>{history.created_at}</td>
                <td>{history.email}</td>
                <td>{history.tel}</td>
                <td>{history.address}</td>
                <td>{history.selectedCompanies ? history.selectedCompanies.map((company, key) => {
                    return company.label
                }).join(', ') : "None"}  </td>
            </tr>
        ));
    };

    render() {
        return (
            <table className="Contact">
                <tbody>
                <tr>
                    <th>Changed Date</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Address</th>
                    <th>Company you sent these to</th>
                </tr>
                {this.renderHistory(this.props.history)}
                </tbody>
            </table>
        );
    }
}