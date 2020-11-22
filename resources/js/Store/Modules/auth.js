import axios from "axios";

const state = {
  user: null,
  loginMessage: "",
  registerMessage: "",
  onlineUsers: [],
  gameStatus: ''
};

const getters = {
  isLogged: state => !!state.user,
  user: state => state.user,
  getLoginMessage: state => state.loginMessage,
  getRegisterMessage: state => state.registerMessage,
  getOnlineUsers: state => state.onlineUsers,
  getGameStatus: state => state.gameStatus
};

const mutations = {
  setUserData (state, userData) {
      state.user = userData;
      axios.defaults.headers.common.Authorization = `Bearer ${userData.access_token}`
  },
  clearUserData (state) {
    state.user = null;
  },
  setLoginMessage(state, message) {
    state.loginMessage = message;
  },
  setRegisterMessage(state, message) {
    state.registerMessage = message;
  },
  setOnlineUsers(state, onlineUsers) {
    state.onlineUsers = onlineUsers;
  },
  addOnlineUser(state, onlineUser) {
    state.onlineUsers.push(onlineUser);
  },
  removeOnlineUser(state, userId) {
    let userToDelete = state.onlineUsers.find(u => u.id === userId);
    let indexOfUser = state.onlineUsers.indexOf(userToDelete);

    if(indexOfUser !== -1) state.onlineUsers.splice(indexOfUser, 1);
    // state.onlineUsers = state.onlineUsers.filter(u => u.user_id !== userId);
  },
  setGameStatus(state, status) {
    state.gameStatus = status;
    setTimeout(() => {
      state.gameStatus = '';
    }, 5000)

  }
};

const actions = {
  login ({ commit }, credentials) {
    return axios
    .post('/auth/login', credentials)
    .then(({ data }) => {
      commit('setUserData', data);
      commit('setLoginMessage', '');
    })
    .catch(e => {
      if(e.response.data.error === 'Unauthorized') {
        commit('setLoginMessage', 'Wrong email or password, please try again');
      } else {
        commit('setLoginMessage', 'Something went wrong, we are working on it');
      }
      //console.log(e.response)
    })
  },
  register ({ commit }, credentials) {
    return axios
      .post('/auth/register', credentials)
      .then(({ data }) => {
        commit('setRegisterMessage', '');
      })
      .catch(e => {
        if(e.response.data.error === 'Unauthorized') {
          commit('setRegisterMessage', 'Wrong email or password, please try again');
        } else {
          commit('setRegisterMessage', 'Something went wrong, we are working on it');
        }
      })
  },
  setUserInfo({commit}, userData) {
      commit('setUserData', userData);
  },
  logout () {
    axios.post('/auth/logout').then(res => {
      console.log(res.data.message);
    }).catch(e => {
      console.log('Error with logout: ', e);
    })
  },
  setLoginMessage({commit}, message) {
    commit('setLoginMessage', message);
  },
  setRegisterMessage({commit}, message) {
    commit('setRegisterMessage', message);
  },
  setOnlineUsersSocket({commit}, users) {
    commit('setOnlineUsers', users);
  },
  addUserFromSocket({commit}, user) {
    commit('addOnlineUser', user);
  },
  removeUserFromSocket({commit}, userId) {
    commit('removeOnlineUser', userId);
  },
  setGameStatus({commit}, status) {
    commit('setGameStatus', status);
  }
};
export default {
    state,
    getters,
    mutations,
    actions
}

