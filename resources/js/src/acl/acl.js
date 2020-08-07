import Vue from "vue";
import { AclInstaller, AclCreate, AclRule } from "vue-acl";
import router from "@/router";

Vue.use(AclInstaller);

let initialRole = "public";

const userInfo = JSON.parse(localStorage.getItem("user"));
if (userInfo && userInfo.role) initialRole = userInfo.role;

export default new AclCreate({
    initial: initialRole,
    notfound: "/dash",
    router,
    acceptLocalRules: true,
    globalRules: {
        guest: new AclRule("public").generate(),
        Admin: new AclRule("Admin").generate(),
        Editor: new AclRule("Editor").or("Admin").generate(),
        Commercial: new AclRule("Commercial").or("Admin").generate(),
        all: new AclRule("Admin")
            .or("Editor")
            .or("Commercial")
            .generate()
    }
});
