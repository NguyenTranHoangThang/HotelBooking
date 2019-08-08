import Vue  from 'vue';
import VueRouter  from 'vue-router';

import home  from '@/js/frontend/community/components/home';
import account  from '@/js/frontend/community/components/account';
import hotel  from '@/js/frontend/community/components/hotel';
import user  from '@/js/frontend/community/components/user';
import login  from '@/js/frontend/community/components/login';
import review  from '@/js/frontend/community/components/review';
import bookingList  from '@/js/frontend/community/components/booking-list';

Vue.use(VueRouter);

const router = new VueRouter({
	mode: 'history',
	routes :[
		{
			path: '/community/',
			name: 'home',
			component: home
		},
		{
			path: '/community/login.html',
			name: 'login',
			component: login
		},
		{
			path: '/community/booking-list',
			name: 'booking-list',
			component: bookingList
		},
		{
			path: '/community/account',
			name: 'account',
			component: account
		},
		{
			path: '/community/review',
			name: 'review',
			component: review
		},
		{
			path: '/hotel/:id',
			name: 'hotel',
			component: hotel
		},
		{
			path: '/community/user/:id',
			name: 'user',
			component: user
		}
	]
});

export default router;
