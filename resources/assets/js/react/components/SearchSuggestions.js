import React from 'react';

var SearchSuggestions = React.createClass({

    componentDidMount : function(){
        var search = $("#searchInput");
        var suggestBox = $('.search-suggestions');
        var borderSize = suggestBox.css('border-width').replace('px','');
        suggestBox.css('min-width', 2.5 * search.outerWidth()-2*borderSize);
        // suggestBox.css('max-width', 2.5 * settings.maxWidth-2*borderSize);
    },
    componentWillReceiveProps: function(nextProps){

    },
    componentDidUpdate : function(prevProps,  prevState){

    },
    render : function(){
        return (
            <div className="search-suggestions z1">
                <ul className="list-group">
                    {function(){
                        if(!this.props.results || this.props.results.length <= 0){
                            return <li className="list-group-item">No result found</li>
                        }else{
                            var html = function(){
                                var htmlInside = [];
                                var counter = 0;
                                for (var key in this.props.results){
                                    if(this.props.results[key].length > 0){
                                        var innerHtml = (
                                            <li key={key} className="list-group-item suggestions-block">
                                                <h4 className="list-group-item-heading">{key}</h4>
                                                <div className="list-group">
                                                    { function(){
                                                        var nodes = [];
                                                        for(var i = 0; i< this.props.results[key].length; i++){
                                                            var item = this.props.results[key][i];
                                                            var selectedClass = this.props.selected == counter ? 'list-group-item active' : 'list-group-item';
                                                            counter++;
                                                            nodes.push(
                                                                <a key={key + '_'+i} href={item.link} className={selectedClass}>
                                                                    <div className="media">
                                                                        <div className="media-left">
                                                                            <img src={item.image} alt="" className="media-object profile-avatar-small img-circle"/>
                                                                        </div>
                                                                        <div className="media-body">
                                                                            <h4 className="media-heading">{item.name}</h4>
                                                                            <small>{item.description}</small>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            );
                                                        }
                                                        return nodes;
                                                    }.bind(this)()}
                                                </div>
                                            </li>
                                        )
                                        htmlInside.push(innerHtml);
                                    }
                                }
                                return htmlInside;
                            }.bind(this)();
                            return (
                                html
                            ) ;


                        }
                    }.bind(this)()}

                </ul>
            </div>
        )
    }
});

export default SearchSuggestions;