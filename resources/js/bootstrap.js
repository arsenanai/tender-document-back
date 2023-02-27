/* eslint-disable import/no-extraneous-dependencies */
import lodash from 'lodash';
import * as Popper from '@popperjs/core';
import 'bootstrap';
import axios from 'axios';

window._ = lodash;
window.Popper = Popper;
window.axios = axios;
window.axios.defaults.withCredentials = true;
