import Notification from "@typo3/backend/notification.js";

class ResponseHandling {
  handleResponse(res, errorMessage) {
    if (res !== null) {
      if (res.error) {
        Notification.error(TYPO3.lang['ai3.notification.generation.requestError'], res.error);
      }
    } else {
      Notification.error(TYPO3.lang['ai3.notification.generation.error'], errorMessage);
    }
  }

  handleSuccess(res, errorMessage) {
    if (res !== null) {
      if (res.error) {
        Notification.error(TYPO3.lang['ai3.notification.generation.requestError'], res.error);
      } else {
        Notification.success('SUCCESS', 'success');
      }
    } else {
      Notification.error(TYPO3.lang['ai3.notification.generation.error'], errorMessage);
    }
  }

  handleError(error, errorMessage) {
    if (error !== null) {
      Notification.error(TYPO3.lang['ai3.notification.generation.requestError'], error);
    } else {
      Notification.error(TYPO3.lang['ai3.notification.generation.error'], errorMessage);
    }
  }

  handleStatusResponse(res, modal) {
    let statusElement = modal.find('.modal-body').find('.spinner-wrapper .status');
    if (
        statusElement !== null
        && res !== null
        && res.error !== null
    ) {
      statusElement.html(res.output);
    }
  }
}

export default new ResponseHandling();
