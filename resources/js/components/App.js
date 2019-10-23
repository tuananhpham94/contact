import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            history:[],
            email: "",
            address: "",
            tel: "",
            helpText: ""
        };
        // this.renderHistory = this.renderHistory.bind(this);
    }
    handleEmailChange(e) {
        this.setState({email:e.target.value,
            helpText: ""});
        console.log('onChange', this.state);
    }
    handleAddressChange(e) {
        this.setState({
            address: e.target.value,
            helpText: ""
        });
        console.log('onChange', this.state);
    }
    handleTelChange(e) {
        this.setState({
            tel: e.target.value,
            helpText: ""
        });
        console.log('onChange', this.state);
    }
    handleSubmit(e) {
        e.preventDefault();
        console.log(this.state.history.length);
        if(this.state.history.length > 0){
            if(this.state.email !== this.state.history[this.state.history.length-1].email ||
                this.state.tel !== this.state.history[this.state.history.length-1].tel ||
                this.state.address !== this.state.history[this.state.history.length-1].address){
                this.createNewHistory();
            } else {
                // duplicate handle
                this.setState({
                    helpText: "Only update if you change at least one of those thing: address, phone or email"
                });
            }
        } else {
            this.createNewHistory();
        }
    }
    createNewHistory () {
        axios.post('/userHistory', {
            address: this.state.address,
            tel: this.state.tel,
            email: this.state.email
        }).then(response => {
            let newData = response.data.history;
            let history = [...this.state.history];
            if (history === "") {
                this.setState({
                    history: [newData],
                    helpText: "Good job on creating new records, do you want to update banks or ird?"
                })
            } else {
                this.setState({
                    history: [...history, newData],
                    helpText: "Good job on creating new records, do you want to update banks or ird?"
                })
            }
            console.log('form handle submit', response);
            console.log(this.state);
        });
    }
    getHistory() {
        axios.get('/userHistory').then((response) => {
            const allHistory = [...response.data.allHistory];
            let email = "";
            let address = "";
            let tel = "";
            if(!allHistory[allHistory.length-1]) {
                email= "";
                address = "";
                tel=""
            } else {
                !allHistory[allHistory.length-1].email ? email = "" : email = allHistory[allHistory.length-1].email;
                !allHistory[allHistory.length-1].address ? address = "" : address = allHistory[allHistory.length-1].address;
                !allHistory[allHistory.length-1].tel ? tel = "" : tel = allHistory[allHistory.length-1].tel;
            }
            this.setState({
                //spread operator to clone all response history from database
                history: allHistory,
                email: email,
                address: address,
                tel: tel,
            });
            console.log("1" + this.state.email);
            console.log(this.state);
        })
    }
    componentDidMount() {
        this.getHistory();
    }
    renderHistory() {
        return this.state.history.map(history => (
            <tr key={history.id}>
                <td>{history.created_at}</td>
                <td>{history.email}</td>
                <td>{history.tel}</td>
                <td>{history.address}</td>
            </tr>
        ));
    }
    render() {
        let table;
        if (this.state.history.length > 0) {
            table = (
                <table className="Contact">
                    <tbody>
                    <tr>
                        <th>Changed Date</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Address</th>
                    </tr>
                    {this.renderHistory()}
                    </tbody>
                </table>
            );
        } else {
            table = this.renderHistory();
        }
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="card">
                        <div className="card-header">App Component</div>

                        <div className="card-body">{this.state.helpText}</div>
                        <form className="detail" onSubmit={(event) => this.handleSubmit(event)}>
                            <div className="form-group row">
                                <label htmlFor="email">Email address</label>
                                <input
                                    onChange={(event) => this.handleEmailChange(event)}
                                    id="email" type="email"
                                    className="form-control"
                                    name="email"
                                    value={this.state.email}
                                    autoFocus />
                            </div>
                            <div className="form-group row">
                                <label htmlFor="tel"> Phone number</label>
                                <input id="tel" type="text"
                                       onChange={(event) => this.handleTelChange(event)}
                                       className="form-control"
                                       name="tel"
                                       value={this.state.tel}
                                       autoFocus />
                            </div>
                            <div className="form-group row">
                                <label htmlFor="address">Address</label>
                                <input id="address" type="text"
                                       onChange={(event) => this.handleAddressChange(event)}
                                       className="form-control"
                                       name="address"
                                       value={this.state.address}
                                       autoFocus />
                            </div>
                            <button type="submit" className="btn btn-primary">
                                Change
                            </button>
                        </form>
                        <hr />

                        <div className="row">
                            {table}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
if (document.getElementById('root')) {
    ReactDOM.render(<App />, document.getElementById('root'));
}
