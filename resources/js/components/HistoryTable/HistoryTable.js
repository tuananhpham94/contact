import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class History extends Component {
    renderHistory(history) {
        return history.map(history => (
            <tr key={history.id}>
                <td>{history.created_at}</td>
                <td>{history.email}</td>
                <td>{history.tel}</td>
                <td>{history.address}</td>
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
                </tr>
                {this.renderHistory(this.props.history)}
                </tbody>
            </table>
        );
    }
}