import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import HistoryTable from './HistoryTable/HistoryTable';
import Form from './Form/Form';

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
    }
    handleAddressChange(e) {
        this.setState({
            address: e.target.value,
            helpText: ""
        });
    }
    handleTelChange(e) {
        this.setState({
            tel: e.target.value,
            helpText: ""
        });
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
        })
    }
    componentDidMount() {
        this.getHistory();
    }
    render() {
        let table;
        this.state.history.length > 0 ? table = <HistoryTable history={this.state.history}/> : table = "";
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="card">
                        <div className="card-header">App Component</div>

                        <div className="card-body">{this.state.helpText}</div>
                        <Form
                            submit={(event) => this.handleSubmit(event)}
                            emailChange={(event) => this.handleEmailChange(event)}
                            email={this.state.email}
                            telChange={(event) => this.handleTelChange(event)}
                            tel={this.state.tel}
                            addressChange={(event) => this.handleAddressChange(event)}
                            address={this.state.address}
                        />
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
