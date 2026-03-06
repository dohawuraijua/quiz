import { createStore } from "vuex";

export const login_state = createStore({
    state() {
        return {
            userDetail: null,
            userRole: [],
            isLogin: false,
        };
    },
    mutations: {
        setuserDetail(state, item) {
            state.userDetail = item;
        },
        setLoginStatus(state, item) {
            state.isLogin = item;
        },
        setuserRole(state, item) {
            state.userRole = item;
            state.isLogin = item && item.length > 0;
        },
        logout(state) {
            state.userDetail = null;
            state.isLogin = false;
            state.userRole = [];
        }
    },
    actions: {
        increment({ commit }) {
            commit("increment");
        },
    },
    getters: {
        count: (state) => state.count,
    },
});
