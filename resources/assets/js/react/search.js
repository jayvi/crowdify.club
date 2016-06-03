/**
 * Created by sohel on 6/2/16.
 */
import React from 'react';
import ReactDOM from 'react-dom';
import SearchBox from './components/SearchBox';
ReactDOM.render(
    <SearchBox searchUrl="/site/search"  />,
    document.getElementById('site-search-box')
);