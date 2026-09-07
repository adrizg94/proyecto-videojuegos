export const useToast = () => {
  const toast = useState("toast", () => ({
    show: false,
    message: "",
  }));
  let toastTimeout;

  const showToast = (message) => {
    clearTimeout(toastTimeout);

    toast.value = {
      show: true,
      message,
    };

    toastTimeout = setTimeout(() => {
      toast.value.show = false;
    }, 3000);
  };

  return {
    toast,
    showToast,
  };
};
