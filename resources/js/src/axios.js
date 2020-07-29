// axios
import axios from "axios";

const baseURL = "";

export default axios.create({
    baseURL,
    headers: {
        Authorization: `Bearer ${localStorage.getItem("jwt")}`
    }
});
