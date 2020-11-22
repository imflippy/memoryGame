

const state = {
  windowWidth: window.screen.width,
  gameActive: false
};

const getters = {
  getWidth: state => state.windowWidth,
  isGameActive: state => state.gameActive
};

const mutations = {
  setGameValue(state, value) {
    state.gameActive = value;
  }
};

const actions = {
  setGameValue({commit}, value) {
    commit('setGameValue', value);
  }
};

export default {
  state,
  getters,
  mutations,
  actions
}
