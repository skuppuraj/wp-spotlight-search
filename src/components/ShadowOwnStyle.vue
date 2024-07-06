<template>
  <shadow-style>
    html,
    body,
    div,
    span,
    applet,
    object,
    iframe,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    pre,
    a,
    abbr,
    acronym,
    address,
    big,
    cite,
    code,
    del,
    dfn,
    em,
    img,
    ins,
    kbd,
    q,
    s,
    samp,
    small,
    strike,
    strong,
    sub,
    sup,
    tt,
    var,
    b,
    u,
    i,
    center,
    dl,
    dt,
    dd,
    ol,
    ul,
    li,
    fieldset,
    form,
    label,
    legend,
    table,
    caption,
    tbody,
    tfoot,
    thead,
    tr,
    th,
    td,
    article,
    aside,
    canvas,
    details,
    embed,
    figure,
    figcaption,
    footer,
    header,
    hgroup,
    menu,
    nav,
    output,
    ruby,
    section,
    summary,
    time,
    mark,
    audio,
    video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }
    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    menu,
    nav,
    section {
        display: block;
    }
    body {
        line-height: 1;
    }
    ol,
    ul {
        list-style: none;
    }
    blockquote,
    q {
        quotes: none;
    }
    blockquote:before,
    blockquote:after,
    q:before,
    q:after {
        content: "";
        content: none;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    :host {
        --accentClrRGB: 74, 97, 195;
        --grey100: #23282d;
        --grey500: #999999;
        --white50: rgba(255, 255, 255, 0.5);
        --borderClr: #ccd0d4;
        --monoSpaceFont: "Anonymous Pro", monospace;
    }
    #wpss-main-container {
        width: 1000px;
        position: fixed;
        top: 100px;
        left: 50%;
        margin-left: -500px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        font-size: 16px;
        background-color: #fff;
        box-shadow: 0 3px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    #wpss-main-container hr {
        margin: 10px;
        height: 0;
        border: 0;
        border-top: 1px solid var(--borderClr);
    }
    #wpss-main-container strong {
        font-weight: bold;
    }
    #wpss-main-container .cmd strong {
        font-weight: 700;
    }
    #wpss-main-container label[for="search-box"] {
        background-color: #f1f1f1;
        border-bottom: 1px solid #ccd0d4;
        border-radius: 10px 10px 0 0;
        cursor: text;
        display: block;
        padding: 15px;
        margin-bottom: 10px;
        position: relative;
    }
    #wpss-main-container label[for="search-box"] input[type="text"] {
        font-size: 18px;
        width: 100%;
        margin-bottom: 3px;
        line-height: 20px;
        text-indent: 2px;
        margin-left: -2px;
        letter-spacing: -0.5px;
    }
    #wpss-main-container label[for="search-box"] .cmd-tag {
        color: rgba(var(--accentClrRGB), 1);
        border: 1px solid rgba(var(--accentClrRGB), 1);
        border-radius: 3px;
        font-family: var(--monoSpaceFont);
        position: absolute;
        right: 10px;
        bottom: 12px;
        font-size: 12px;
        font-weight: 600;
        padding: 1px 2px 1px 4px;
    }
    #wpss-main-container label[for="search-box"] input[type="text"].cmd {
        font-family: var(--monoSpaceFont);
    }
    #wpss-main-container label[for="search-box"] input[type="text"]::placeholder,
    #wpss-main-container label[for="search-box"] input[type="text"]::-moz-placeholder,
    #wpss-main-container label[for="search-box"] input[type="text"]::-webkit-input-placeholder,
    #wpss-main-container label[for="search-box"] input[type="text"]:-ms-input-placeholder {
        color: pink;
    }
    #wpss-main-container label[for="search-box"] input[type="text"],
    #wpss-main-container label[for="search-box"] input[type="text"]:hover,
    #wpss-main-container label[for="search-box"] input[type="text"]:focus {
        border: 0;
        outline: 0;
        background: transparent;
    }
    #wpss-main-container .ul.horiz-tabs {
        display: flex;
        margin: 0px 15px;
        flex-wrap: wrap;
        list-style-type: none; /* Remove bullets */
        padding: 0; /* Remove padding */
    }
    #wpss-main-container .ul.horiz-tabs .li {
        font-size: 11px;
        padding: 7px 7px;
        cursor: pointer;
        text-transform: uppercase;
        border-radius: 7px;
        display: flex;
        align-items: center;
    }
    #wpss-main-container .ul.horiz-tabs .li label {
        display: flex;
        align-items: center;
    }
    #wpss-main-container .ul.horiz-tabs .li.active,
    #wpss-main-container .ul.horiz-tabs .li:hover {
        background-color: rgba(var(--accentClrRGB), 0.3);
    }
    #wpss-main-container .ul.horiz-tabs .li:not(:last-child) {
        margin-right: 7px;
    }
    #wpss-main-container .options-title {
        text-transform: uppercase;
        color: var(--grey500);
        font-size: 11px;
        padding: 5px 15px;
    }
    #wpss-main-container .search-help-text,
    #wpss-main-container .horiz-tabs,
    #wpss-main-container .options-title,
    #wpss-main-container .options-list,
    #wpss-main-container .footer {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    #wpss-main-container .options-list li {
        padding: 8px 15px;
        cursor: pointer;
    }
    #wpss-main-container .options-list li a{
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #wpss-main-container .options-list li .option-item {
        font-size: 14px;
        color: var(--grey100);
    }
    #wpss-main-container .options-list.cmd li .option-item {
        font-size: 15px;
    }
    #wpss-main-container .options-list li .option-desc {
        font-size: 12px;
        color: var(--grey500);
        margin-top: 3px;
    }
    #wpss-main-container .options-list li:hover,
    #wpss-main-container .options-list li.active {
        background-color: rgba(var(--accentClrRGB), 1);
    }
    #wpss-main-container .options-list li:hover .option-item,
    #wpss-main-container .options-list li.active .option-item {
        color: #fff;
    }
    #wpss-main-container .options-list li:hover .option-desc,
    #wpss-main-container .options-list li.active .option-desc {
        color: var(--white50);
    }
    #wpss-main-container .cmd {
        font-family: var(--monoSpaceFont);
    }
    #wpss-main-container .search-help-text {
        font-size: 11px;
        color: var(--grey500);
    }
    #wpss-main-container .search-help-text.cmd {
        font-size: 12px;
    }
    #wpss-main-container .footer {
        padding: 4px 5px 6px 5px;
        border-radius: 0 0 10px 10px;
        background-color: #f1f1f1;
        border-top: 1px solid #ccd0d4;
        font-size: 11px;
        color: var(--grey500);
        margin-top: 10px;
        display: flex;
        align-items:center;
    }
    #wpss-main-container .footer a {
        padding: 5px;
        position: relative;
        color: var(--grey500);
        text-decoration: none;
    }
    #wpss-main-container .footer a:hover {
        text-decoration: underline;
        cursor: pointer;
    }
    #wpss-main-container .footer a:visited {
        color: var(--grey500);
    }
    #wpss-main-container .footer a.share-love::after {
        content: "";
        position: absolute;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 34.87 28.79'%3E%3Cdefs%3E%3Cstyle%3E.cls-1%7Bfill:%23e74c3c;%7D%3C/style%3E%3C/defs%3E%3Cg id='Layer_2' data-name='Layer 2'%3E%3Cg id='Layer_1-2' data-name='Layer 1'%3E%3Cpath class='cls-1' d='M33.94,4.86A8.79,8.79,0,0,0,22.15.93a8.68,8.68,0,0,0-2.29,1.65,3.44,3.44,0,0,1-4.85,0h0A8.79,8.79,0,0,0,2.58,15h0L14.65,27.6a3.87,3.87,0,0,0,5.46.12l.11-.12L32.29,15A8.79,8.79,0,0,0,33.94,4.86Z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        width: 10px;
        height: 8px;
        margin: 2px 0 0 3px;
    }
    .search-result-wrapper{
      display: flex;
    }
    .search-container-left{
      width: 50%;
      position: relative;
      box-shadow: inset 1px 1px 2px hsla(230, 6%, 60%, .24), inset 2px 2px 8px hsla(230, 6%, 60%, .1);
      max-height: 60vh;
      overflow-y: auto;
    }
    .search-container-right{
      width: 50%;
      position: relative;
      box-shadow: inset 1px 1px 2px hsla(230, 6%, 60%, .24), inset 2px 2px 8px hsla(230, 6%, 60%, .1);
      max-height: 60vh;
      overflow-y: auto;
    }

    .search-container-right ul li{
      padding-top: 10px;
      padding-bottom: 10px;
      display: flex;
      justify-content: space-between;
      margin: 10px 20px;
      border-bottom: 1px solid #e4e7ec;
    }

    .search-container-right .more-actions{
        display: flex;
        margin: 10px 0px;
    }
    .search-container-right .more-options-title{
        color: var(--grey500);
    }
    .search-container-right .more-actions span{ 
        padding-right: 10px;
    }
    .search-box-wrapper{
        display: flex;
    }
    .search-box-wrapper .search-icon{
        height: 20px;
        padding: 0px 10px 0px 0px;
    }
    .search-box-wrapper .loading{
        height: 20px;
        width: 20px;
    }
    .enter-icon{
        color: #fff;
    }
    <!-- .arrow-section{
        display: flex;
        flex-direction: row-reverse;
    } -->

  </shadow-style>
</template>

<script>
import { ShadowStyle } from 'vue-shadow-dom';

export default {
  name: 'ShadowOwnStyle',
  components: {
    ShadowStyle,
  },
};
</script>
