export default function profilePage() {
    return {
        tab: "profile",
        profileView: "info",
        settingsView: "cards",

        init() {
            const hash = window.location.hash.replace("#", "");
            if (["profile", "settings", "privacy"].includes(hash)) {
                this.tab = hash;
            }
        },
    };
}
