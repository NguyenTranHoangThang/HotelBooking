import Vue  from 'vue';
import VueRouter  from 'vue-router';

import home  from '@/js/frontend/components/home';
import about  from '@/js/frontend/components/about';
import hotel  from '@/js/frontend/components/hotel';
import user  from '@/js/frontend/components/user';
import contact  from '@/js/frontend/components/contact';
import searching  from '@/js/frontend/components/searching';
import policy  from '@/js/frontend/components/policy';
import review  from '@/js/frontend/community/components/review';

Vue.use(VueRouter);

const router = new VueRouter({
	mode: 'history',
	routes :[
		{
			path: '/',
			name: 'home',
			component: home
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
		},
		{
			path: '/community/review/',
			name: 'review',
			component: review
		},
		{
			path: '/contact.html',
			name: 'contact',
			component: contact
		},
		{
			path: '/about.html',
			name: 'about',
			component: about
		},
		{
			path: '/searching',
			name: 'searching',
			component: searching
		},
		{
			path: '/policies.html',
			name: 'policy',
			component: policy
		}
	]
});

export default router;
