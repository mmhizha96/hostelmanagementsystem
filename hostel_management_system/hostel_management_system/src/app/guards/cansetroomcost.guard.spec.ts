import { TestBed } from '@angular/core/testing';

import { CansetroomcostGuard } from './cansetroomcost.guard';

describe('CansetroomcostGuard', () => {
  let guard: CansetroomcostGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CansetroomcostGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
