import React from 'react';
import SearchSuggestions from './SearchSuggestions';

var SearchBox = React.createClass({
    serverRequest : null,
    getInitialState : function(){
        return {
            results : [],
            showSuggestion : false,
            selected : -1,
            search : ''
        }
    },
    componentDidMount : function(){

    },
    componentDidUpdate : function(prevProps,  prevState){

    },
    componentWillUnmount : function(){

    },
    cancelServerRequest : function(){
        if(this.serverRequest){
            this.serverRequest.abort();
        }
    },
    onChange : function(e){
        this.cancelServerRequest();
        var value = e.target.value;
        if(!value || value == '' || value.length < 3){
            this.setState({
                results : [],
                showSuggestion : false,
                search : value,
                selected : -1
            });
        }else{
            this.serverRequest = $.ajax({
                url: this.props.searchUrl,
                type: 'GET',
                data: { search : value},
                success: function(response,status,xhr) {
                    //console.log(response.results);
                    if(response){
                        this.setState({
                            results : response.results.suggests,
                            showSuggestion : true,
                            search : value,
                            selected : -1
                        });
                    }

                }.bind(this),
                error: function(xhr,status,error){
                    var error = {};
                    if(xhr && xhr.responseText){
                        var error = JSON.parse(xhr.responseText);
                    }
                }.bind(this)
            });

            this.setState({
                search : value
            });

        }

    },
    onFocus : function(e){
        if((!e.target.value || e.target.value == '') || (!this.state.search || this.state.search == '')){
            this.setState({
                showSuggestion : false,
                results : [],
                search : ''
            });
        }else{
            this.setState({
                showSuggestion : true,
            });
        }

    },
    onBlur : function(e){
        this.cancelServerRequest();
        setTimeout(function(){
            this.setState({
                showSuggestion : false
            });
        }.bind(this), 200);

    },
    setSelected : function(index){
        var indexCounter = 0;
        var totalCounter = 0;
        for (var key in this.state.results){
           totalCounter += this.state.results[key].length;
        }
        if(index >= totalCounter ){
            this.setState({
               selected : 0
            });
        }else if(index < 0){
            this.setState({
                selected : totalCounter - 1
            });
        }else{
            this.setState({
                selected : index
            });
        }

    },
    handleEnter : function(){
        var totalCounter = 0;
        var item = null;
        var shouldBreak = false;
        for (var key in this.state.results){
            if(this.state.results[key].length > 0){
                for(var i = 0; i < this.state.results[key].length ;i++){
                    if(totalCounter == this.state.selected){
                        item = this.state.results[key][i];
                        shouldBreak = true;
                        break;
                    }
                    totalCounter++;
                }
            }
            if(shouldBreak){
                break;
            }
        }
        //console.log(item);
        if(item){
            window.location.href = item.link;
        }
    },
    onKeyDown : function(e){
        if(this.state.showSuggestion){
            var update = false;
            if(e.keyCode == 38){
                this.setSelected(this.state.selected - 1);
            }else if(e.keyCode == 40){
                this.setSelected(this.state.selected + 1);
            }
            else if(e.keyCode == 13){
                this.handleEnter();
            }

        }
    },

    render : function(){
        return (
            <div>
                <input id="searchInput" className="searchInput" autoComplete="off" type="text" onKeyDown={this.onKeyDown} onFocus={this.onFocus} onBlur={this.onBlur} onChange={this.onChange} name={this.props.name} placeholder={this.props.placeHolder}/>
                {function(){
                    if(this.state.showSuggestion){
                        return  <SearchSuggestions results={this.state.results} selected={this.state.selected}/>
                    }else{
                        return false;
                    }
                }.bind(this)()}
            </div>
        )
    }
});

export default SearchBox;